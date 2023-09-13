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

    readOperators = import ./lib/operators.nix {inherit lib;};
    operatorFiles = builtins.filter (file: lib.hasSuffix ".nix" (baseNameOf file)) (lib.filesystem.listFilesRecursive ./operators);
    operators = lib.pipe operatorFiles [
      (map readOperators)
      (map (operator: {
        name = operator.hafas-id;
        value = operator;
      }))
      builtins.listToAttrs
    ];

    readLines = import ./lib/lines.nix {inherit lib operators;};

    lineFiles = builtins.filter (file: lib.hasSuffix ".nix" (baseNameOf file)) (lib.filesystem.listFilesRecursive ./lines);
    lines = lib.pipe lineFiles [
      (map readLines)
      lib.flatten
    ];
  in {
    inherit operators lines;

    formatter = forAllSystems (pkgs: pkgs.alejandra);
    packages = forAllSystems (pkgs: let
      operatorsValue = builtins.attrValues operators;
      linesValue = map (line:
        builtins.removeAttrs line ["operator"]
        // {
          operatorHafasId = line.operator.hafas-id or null;
          operatorName = line.operator.name or null;
        })
      lines;
    in {
      operators-json = pkgs.callPackage ./pkgs/json.nix {
        value = operatorsValue;
        name = "operators.json";
      };
      operators-csv = pkgs.callPackage ./pkgs/csv.nix {
        value = operatorsValue;
        name = "operators.csv";
      };
      lines-json = pkgs.callPackage ./pkgs/json.nix {
        value = linesValue;
        name = "lines.json";
      };
      lines-csv = pkgs.callPackage ./pkgs/csv.nix {
        value = linesValue;
        name = "lines.csv";
      };
      operator-docs = pkgs.callPackage ./pkgs/docs.nix { 
        options = [(import ./lib/operator-module.nix { 
          inherit lib;
        })];
      };
      line-docs = pkgs.callPackage ./pkgs/docs.nix {
        options = [(import ./lib/line-module.nix { inherit lib; })];
      };
    });
  };
}
