{
  options,
  lib,
  runCommand,
  nixosOptionsDoc,
}: let 
  eval = lib.evalModules {
    modules = options ++ [{
      config._module.check = false;
    }];
  };
  optionsDoc = nixosOptionsDoc {
    options = (lib.filterAttrs (k: _: k != "_module")) (builtins.trace eval.options eval.options);
  };
in 
  optionsDoc.optionsCommonMark
