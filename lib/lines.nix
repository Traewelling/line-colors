{
  lib,
  operators,
}: path: let
  normalizeLines = cfg:
    map (line: {
      inherit (line) name hafas-id;
      inherit (cfg) operator;
      backgroundColor = line.backgroundColor or cfg.defaultBackgroundColor;
      textColor = line.textColor or cfg.defaultTextColor;
      shape = line.shape or cfg.defaultShape;
    })
    cfg.lines;
  lineModule = import ./line-module.nix {inherit lib;};
  modules = lib.evalModules {
    modules = [
      (lineModule)
      {imports = [path];}
    ];
    specialArgs = {inherit operators;};
  };
in
  normalizeLines modules.config
