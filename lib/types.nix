{lib}: {
  color = lib.types.str;
  shape = lib.types.enum [
    "rectangle"
    "pill"
    "rectangle-rounded-corner"
    "trapezoid"
    "hexagon"
  ];
}
