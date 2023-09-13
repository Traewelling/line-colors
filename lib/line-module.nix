{lib}:
let
  types = import ./types.nix { inherit lib; };
  operatorModule = import ./operator-module.nix { inherit lib; };
in
{ config, ... }:
{
  options = {
    operator = lib.mkOption {
      type = with lib.types; nullOr (submodule operatorModule);
      example = lib.literalExpression "operators.db-fernverkehr-ag";
      description = lib.mdDoc "The Operator of the Lines";
    };
    defaultBackgroundColor = lib.mkOption {
      type = lib.types.nullOr types.color;
      default = null;
      description = "Default background color for this file";
    };
    defaultTextColor = lib.mkOption {
      type = lib.types.nullOr types.color;
      default = null;
      description = "Default text color for this file";
    };
    defaultShape = lib.mkOption {
      type = lib.types.nullOr types.shape;
      default = null;
      description = "Default shape for this file";
    };
    lines = lib.mkOption {
      type = with lib.types;
        listOf (submodule {
          options = {
            name = lib.mkOption {
              type = lib.types.str;
              description = "The displayed name of the line";
              example = lib.mdDoc "RB86";
            };
            hafas-id = lib.mkOption {
              type = lib.types.str;
              description = "The Hafas ID of the line";
            };
            backgroundColor = lib.mkOption {
              type = types.color;
              description = "The background color of the line";
              example = lib.mdDoc "#acabee";
              default = config.defaultBackgroundColor;
            };
            textColor = lib.mkOption {
              type = types.color;
              description = "The text color of the line";
              example = lib.mdDoc "#ffffff";
              default = config.defaultTextColor;
            };
            shape = lib.mkOption {
              type = types.shape;
              description = "Default shape for this file";
              default = config.defaultShape;
            };
          };
        });
      description = lib.mdDoc "The Lines";
    };
  };
}
