{
  stdenvNoCC,
  operators,
  jq,
}:
stdenvNoCC.mkDerivation {
  name = "operators.json";

  dontUnpack = true;
  dontConfigure = true;
  dontFixup = true;

  buildPhase = ''
    runHook preBuild
    echo '${builtins.toJSON (builtins.attrValues operators)}' | ${jq}/bin/jq > $out
    runHook postBuild
  '';
}
