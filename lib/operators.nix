{lib}: path: let
  operatorModule = import ./operator-module.nix {inherit lib;};
  modules = lib.evalModules {
    modules = [operatorModule path];
  };
in
  modules.config
