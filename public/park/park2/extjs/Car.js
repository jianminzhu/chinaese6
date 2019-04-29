"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var RangeUtil_1 = require("./RangeUtil");
var Car = /** @class */ (function () {
    function Car() {
    }
    Car.genCarImgs = function (carNamePrefixs, start, end, duration) {
        if (start === void 0) { start = 0; }
        if (end === void 0) { end = 11; }
        if (duration === void 0) { duration = 500; }
        return Car.make(RangeUtil_1.RangeUtil.range(start, end, function (i) {
            return "" + carNamePrefixs + i + ".png";
        }), duration);
    };
    Car.toTextures = function (imgs) {
        var textures = [];
        for (var _i = 0, imgs_1 = imgs; _i < imgs_1.length; _i++) {
            var img = imgs_1[_i];
            textures.push({ textures: img });
        }
        return textures;
    };
    Car.make = function (carImgs, duration) {
        if (duration === void 0) { duration = 500; }
        var Sprite = spritejs.Sprite;
        var s = new Sprite(carImgs[0]);
        s.animate(Car.toTextures(carImgs), {
            duration: duration,
            direction: 'alternate',
            iterations: Infinity,
        });
        return s;
    };
    return Car;
}());
exports.Car = Car;
