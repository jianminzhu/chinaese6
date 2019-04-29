"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var PD_1 = require("./PD");
var jquery_1 = require("jquery");
jquery_1.default(function () {
    var pd = new PD_1.PD("#container", 700, 700);
    var car = pd.addCar("car1", 100, 100);
    setInterval(function () {
        car.attr({ pos: [100 + Math.floor(Math.random() * 100), 100 + Math.floor(Math.random() * 100)] });
    });
});
