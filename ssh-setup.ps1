$ErrorActionPreference = 'Stop'

$homeDir = $HOME
if (-not $homeDir) { $homeDir = [Environment]::GetFolderPath('UserProfile') }

$sshDir = Join-Path $homeDir '.ssh'
if (-not (Test-Path -LiteralPath $sshDir)) {
    New-Item -ItemType Directory -Force -Path $sshDir | Out-Null
}

$keyPath = Join-Path $sshDir 'pirates_ed25519'

# Generate key if it doesn't already exist (empty passphrase) via redirected stdin
if (-not (Test-Path -LiteralPath $keyPath)) {
    $psi = New-Object System.Diagnostics.ProcessStartInfo
    $psi.FileName = 'ssh-keygen'
    $psi.Arguments = "-q -t ed25519 -f `"$keyPath`""
    $psi.UseShellExecute = $false
    $psi.RedirectStandardInput = $true
    $psi.RedirectStandardOutput = $true
    $psi.RedirectStandardError = $true
    $proc = [System.Diagnostics.Process]::Start($psi)
    # Send two newlines: passphrase and confirmation as empty
    $proc.StandardInput.WriteLine()
    $proc.StandardInput.WriteLine()
    $proc.StandardInput.Flush()
    $proc.StandardInput.Close()
    $proc.WaitForExit()
    if ($proc.ExitCode -ne 0) {
        $err = $proc.StandardError.ReadToEnd()
        throw "ssh-keygen failed with code $($proc.ExitCode): $err"
    }
}

# Ensure ssh-agent is running
try {
    $svc = Get-Service -Name 'ssh-agent' -ErrorAction Stop
    if ($svc.Status -ne 'Running') {
        if ($svc.StartType -eq 'Disabled') { Set-Service -Name 'ssh-agent' -StartupType Manual }
        Start-Service 'ssh-agent'
    }
} catch {
    # On some systems, ssh-agent service may not exist; continue silently
}

# Add key to agent (ignore errors if already added)
try {
    & ssh-add $keyPath | Out-Null
} catch { }

# Output public key
Get-Content -LiteralPath ($keyPath + '.pub')


