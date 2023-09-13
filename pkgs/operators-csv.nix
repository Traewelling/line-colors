{
  stdenvNoCC,
  operators,
  nushell,
}:
stdenvNoCC.mkDerivation {
  name = "operators.csv";

  dontUnpack = true;
  dontConfigure = true;
  dontFixup = true;

  buildPhase = ''
    runHook preBuild
    echo '${builtins.toJSON (builtins.attrValues operators)}' | nu --stdin -c "from json | to csv" > $out
    runHook postBuild
  '';

  buildInputs = [nushell];
}
