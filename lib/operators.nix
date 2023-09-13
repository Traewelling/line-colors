{lib}: path: let
  modules = lib.evalModules {
    modules = [
      {
        imports = [path];
        options = {
          name = lib.mkOption {
            type = lib.types.str;
            description = lib.mdDoc "Name of the Operator";
          };
          hafas-id = lib.mkOption {
            type = lib.types.str;
            description = lib.mdDoc "Hafas ID of the Operator";
          };
        };
      }
    ];
  };
in
  modules.config
