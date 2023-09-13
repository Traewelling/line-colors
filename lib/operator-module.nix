{lib}: {
  options.name = lib.mkOption {
    type = lib.types.str;
    description = lib.mdDoc "Name of the Operator";
  };
  options.hafas-id = lib.mkOption {
    type = lib.types.str;
    description = lib.mdDoc "Hafas ID of the Operator";
  };
}
