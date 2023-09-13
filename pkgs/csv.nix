{
  stdenvNoCC,
  value,
  nushell,
  name,
}:
stdenvNoCC.mkDerivation {
  inherit name;

  dontUnpack = true;
  dontConfigure = true;
  dontFixup = true;

  buildPhase = ''
    runHook preBuild
    echo '${builtins.toJSON value}' | nu --stdin -c "from json | to csv" > $out
    runHook postBuild
  '';

  buildInputs = [nushell];
}
