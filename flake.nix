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
    
    readOperators = import ./lib/operators.nix { inherit lib; };
    operatorFiles = builtins.filter (file: lib.hasSuffix ".nix" (baseNameOf file)) (lib.filesystem.listFilesRecursive ./operators);
    operators = lib.pipe operatorFiles [
      (map readOperators)
      (map (operator: { name = operator.hafas-id; value = operator; }))
      builtins.listToAttrs
    ];
  in {
    inherit operators;

    formatter = forAllSystems (pkgs: pkgs.alejandra);
    packages = forAllSystems (pkgs: {
      operators-json = pkgs.callPackage ./pkgs/operators-json.nix {inherit operators;};
      operators-csv = pkgs.callPackage ./pkgs/operators-csv.nix {inherit operators;};
    });
  };
}
