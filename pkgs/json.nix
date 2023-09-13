{
  stdenvNoCC,
  value,
  name,
  jq,
}:
stdenvNoCC.mkDerivation {
  inherit name;

  dontUnpack = true;
  dontConfigure = true;
  dontFixup = true;

  buildPhase = ''
    runHook preBuild
    echo '${builtins.toJSON value}' | ${jq}/bin/jq > $out
    runHook postBuild
  '';
}
