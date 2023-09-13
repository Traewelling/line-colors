{
  description = "Collection of machine readable public transport operators and line colors";

  inputs.nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";

  outputs = {
    self,
    nixpkgs,
  }: let
    lib = nixpkgs.lib;
    forAllSystems = function:
      nixpkgs.lib.genAttrs [
        "x86_64-linux"
        "aarch64-linux"
        "x86_64-darwin"
        "aarch64-darwin"
      ] (system: function nixpkgs.legacyPackages.${system});

    readOperators = path: let
      dir = builtins.readDir path;
      files = builtins.attrNames (lib.filterAttrs (name: type: type == "regular" && lib.hasSuffix ".nix" name) dir);
      directories = builtins.attrNames (lib.filterAttrs (name: type: type == "directory") dir);
      operators = map (file: (import "${path}/${file}")) files;
      subOperators =
        lib.optionals ((builtins.length directories) > 0)
        (lib.flatten (map (dir: readOperators "${path}/${dir}") directories));
    in
      operators ++ subOperators;

    operators = readOperators ./operators;

    modules = lib.evalModules {
      modules = [
        {
          options.operators = lib.mkOption {
            type = with lib.types; listOf (submodule {
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
            });
          };
        }
        ({
          inherit operators;
        })
      ];
    };
  in {
    inherit (modules.config) operators;
    formatter = forAllSystems (pkgs: pkgs.alejandra);
    packages = forAllSystems (pkgs: {
      operators-json = pkgs.callPackage ./pkgs/operators-json.nix {inherit operators;};
      operators-csv = pkgs.callPackage ./pkgs/operators-csv.nix {inherit operators;};
    });
  };
}
