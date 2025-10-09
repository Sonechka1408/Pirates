param(
    [Parameter(Mandatory = $true)][string]$FtpHost,
    [Parameter(Mandatory = $true)][string]$FtpUser,
    [Parameter(Mandatory = $true)][string]$FtpPassword,
    [Parameter(Mandatory = $true)][string]$LocalDir,
    [ValidateSet('download','upload')][string]$Mode = 'download'
)

$ErrorActionPreference = 'Stop'

function Ensure-DirectoryExists([string]$path) {
    if ([string]::IsNullOrWhiteSpace($path)) { return }
    if (Test-Path -LiteralPath $path) {
        $item = Get-Item -LiteralPath $path -ErrorAction SilentlyContinue
        if ($item -and -not $item.PSIsContainer) {
            $timestamp = Get-Date -Format 'yyyyMMddHHmmss'
            $backup = "$path.bak-$timestamp"
            Move-Item -LiteralPath $path -Destination $backup -Force
        } else {
            return
        }
    }
    [void][System.IO.Directory]::CreateDirectory($path)
}

# Prepare WinSCP portable
$winscpRoot = Join-Path -Path $PSScriptRoot -ChildPath 'winscp'
Ensure-DirectoryExists $winscpRoot
$winscpCom = Get-ChildItem -Path $winscpRoot -Recurse -Filter 'WinSCP.com' -ErrorAction SilentlyContinue | Select-Object -First 1

# Try to ensure WinSCP portable exists; if fails, fallback to native FTP
try {
    if (-not $winscpCom) {
        $zipPath = Join-Path $winscpRoot 'winscp.zip'
        Invoke-WebRequest -Uri 'https://winscp.net/download/WinSCP-6.3.4-Portable.zip' -OutFile $zipPath -UseBasicParsing
        try {
            if (Get-Command Expand-Archive -ErrorAction SilentlyContinue) {
                Expand-Archive -Path $zipPath -DestinationPath $winscpRoot -Force
            }
            else {
                Add-Type -AssemblyName System.IO.Compression.FileSystem
                [System.IO.Compression.ZipFile]::ExtractToDirectory($zipPath, $winscpRoot)
            }
        }
        finally {
            if (Test-Path $zipPath) { Remove-Item $zipPath -Force }
        }
        $winscpCom = Get-ChildItem -Path $winscpRoot -Recurse -Filter 'WinSCP.com' | Select-Object -First 1
    }
}
catch {
    $winscpCom = $null
}

Ensure-DirectoryExists $LocalDir

if ($winscpCom) {
    $winscpPath = $winscpCom.FullName
    $openCmd = "open ftp://$FtpUser@$FtpHost/ -passive=on -explicitssl=off -implicitssl=off -rawsettings SendBuf=0"
    $syncCmd = if ($Mode -eq 'download') { "synchronize local `"$LocalDir`" /" } else { "synchronize remote `"$LocalDir`" /" }
    $argsList = @('/ini=nul','/command',$openCmd,("-password=" + $FtpPassword),$syncCmd,'exit')
    & $winscpPath @argsList
    if ($LASTEXITCODE -ne 0) { throw "WinSCP exited with code $LASTEXITCODE" }
    Write-Host "Success: $Mode completed between $FtpHost and $LocalDir" -ForegroundColor Green
    return
}

# Native FTP fallback (download only)
function Get-FtpResponse($uri, [string]$method) {
    $request = [System.Net.FtpWebRequest]::Create($uri)
    $request.Method = $method
    $request.Credentials = New-Object System.Net.NetworkCredential($FtpUser, $FtpPassword)
    $request.UsePassive = $true
    $request.UseBinary = $true
    $request.KeepAlive = $false
    return $request.GetResponse()
}

function Parse-FtpListLine([string]$line) {
    # Supports typical Unix LIST format; fall back to treating as file
    $isDir = $false
    $name = $line
    if ($line.Length -gt 0) {
        if ($line[0] -eq 'd') { $isDir = $true }
        $parts = $line -split '\s+' | Where-Object { $_ -ne '' }
        if ($parts.Length -ge 9) { $name = ($parts[8..($parts.Length-1)] -join ' ') }
    }
    [PSCustomObject]@{ Name = $name; IsDirectory = $isDir }
}

function Mirror-FtpDirectory([string]$baseUri, [string]$localPath) {
    Ensure-DirectoryExists $localPath
    try {
        $resp = Get-FtpResponse -uri $baseUri -method ([System.Net.WebRequestMethods+Ftp]::ListDirectoryDetails)
    }
    catch {
        return
    }
    $reader = New-Object System.IO.StreamReader($resp.GetResponseStream())
    $listing = $reader.ReadToEnd()
    $reader.Close(); $resp.Close()
    foreach ($raw in ($listing -split "`r?`n")) {
        if ([string]::IsNullOrWhiteSpace($raw)) { continue }
        $entry = Parse-FtpListLine $raw
        if ($entry.Name -eq '.' -or $entry.Name -eq '..') { continue }
        $remoteUri = ($baseUri.TrimEnd('/')) + '/' + $entry.Name
        $localChild = Join-Path $localPath $entry.Name
        if ($entry.IsDirectory) {
            Mirror-FtpDirectory $remoteUri $localChild
        }
        else {
            $dresp = Get-FtpResponse -uri $remoteUri -method ([System.Net.WebRequestMethods+Ftp]::DownloadFile)
            $stream = $dresp.GetResponseStream()
            Ensure-DirectoryExists (Split-Path -Parent $localChild)
            $fs = [System.IO.File]::Open($localChild, [System.IO.FileMode]::Create)
            $stream.CopyTo($fs)
            $fs.Close(); $stream.Close(); $dresp.Close()
        }
    }
}

if ($Mode -ne 'download') {
    throw 'Native FTP fallback currently supports Mode=download only.'
}

$rootUri = 'ftp://' + $FtpHost
Mirror-FtpDirectory $rootUri $LocalDir
Write-Host "Success: download completed via native FTP between $FtpHost and $LocalDir" -ForegroundColor Green


