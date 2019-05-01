$(function () {
    var id = "container";
    var sel = "#" + id;
    if ($(sel).length == 0) {
        $("body").prepend("<div id=\"" + id + "\" style=\"position:absolute;left: 0;top: 0px;width: 3427px;height:1920px;z-index: 10\"></div>");
    }
    var map = new ParkMap(sel);
    map.addCar({ id: "car1", pos: [470, 203], size: [100, 100], textures: "images/cars/pink/1.png" });
    map.addCar({ id: "car4", pos: [470, 203], size: [100, 100], textures: "images/cars/violet/1.png" });
    map.movePoints("car1", [[470, 203], [770, 418], [240, 628], [515, 913]], 10 * (1 + Math.random()));
    map.movePoints("car4", [[470, 203], [770, 418], [240, 628], [515, 913]].reverse(), 10 * (1 + Math.random()));
    map.addCar({ id: "car2", pos: [810, 80], size: [100, 100], textures: "images/cars/blue/1.png" });
    map.addCar({ id: "car5", pos: [810, 80], size: [100, 100], textures: "images/cars/green/1.png" });
    map.movePoints("car2", [[810, 80], [1120, 260], [790, 438], [1050, 638], [543, 916]].reverse(), 10 * (1 + Math.random()));
    map.movePoints("car5", [[810, 80], [1120, 260], [790, 438], [1050, 638], [543, 916]], 10 * (1 + Math.random()));
    map.addCar({ id: "car3", pos: [1170, 20], size: [100, 100], textures: "images/cars/green/1.png" });
    map.addCar({ id: "car6", pos: [1170, 20], size: [100, 100], textures: "images/cars/pink/1.png" });
    map.movePoints("car3", [[1170, 20], [1740, 250], [1115, 615], [1390, 830], [1390, 830]], 10 * (1 + Math.random()));
    map.movePoints("car6", [[1170, 20], [1170, 20], [1740, 250], [1740, 250], [1115, 615], [1390, 830]].reverse(), 10 * (1 + Math.random()));
});
