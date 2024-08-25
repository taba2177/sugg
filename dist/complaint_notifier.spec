# -*- mode: python ; coding: utf-8 -*-


a = Analysis(
    ['complaint_notifier.py'],
    pathex=[],
    binaries=[],
    datas=[('build/complaint_notifier', 'dist/complaint_notifier.exe')],
    hiddenimports=['win10toast_click','win10toast'],
    hookspath=[],
    hooksconfig={},
    runtime_hooks=[],
    excludes=[],
    noarchive=False,
    optimize=0,
)
pyz = PYZ(a.pure)

exe = EXE(
    pyz,
    a.scripts,
    a.binaries,
    a.datas,
    [],
    name='complaint_notifier',
    debug=False,
    bootloader_ignore_signals=False,
    strip=False,
    upx=False,  # Disable UPX compression if needed
    upx_exclude=[],  # Or exclude specific files from UPX
    runtime_tmpdir=None,
    console=True,
    disable_windowed_traceback=False,
    argv_emulation=False,
    target_arch=None,
    codesign_identity=None,
    entitlements_file=None,
)

