var MapCars = /** @class */ (function () {
    function MapCars(groupCars, spritejs) {
        if (spritejs === void 0) { spritejs = window.spritejs; }
        this.groupCars = groupCars;
        this.spritejs = spritejs;
        this.cars = {};
        this.carAnimates = {};
    }
    MapCars.prototype.getCar = function (id) {
        return this.cars[id];
    };
    MapCars.prototype.addCar = function (_a) {
        var id = _a.id, _b = _a.pos, x = _b[0], y = _b[1], _c = _a.size, _d = _c[0], width = _d === void 0 ? 80 : _d, _e = _c[1], height = _e === void 0 ? 80 : _e, textures = _a.textures;
        var Sprite = this.spritejs.Sprite;
        var car = new Sprite(textures);
        car.attr({
            anchor: [0, 0.3],
            size: [width, height],
            pos: [x, y],
        });
        this.cars[id] = car;
        car.animate(this.genCarImgs(textures), {
            duration: 2000,
            iterations: Infinity,
            direction: 'alternate',
        });
        this.groupCars.append(car);
        return car;
    };
    MapCars.prototype.genCarImgs = function (img) {
        var prefix = img.replace(/\d{1,2}\.png$/, "");
        var arr = [];
        for (var i = 1; i <= 12; i++) {
            arr.push({ textures: "" + prefix + (i + 1) + ".png" });
        }
        return arr;
    };
    ;
    MapCars.prototype.move = function (id, _a, durationSecond, isDemo) {
        var x = _a[0], y = _a[1];
        if (durationSecond === void 0) { durationSecond = 2; }
        if (isDemo === void 0) { isDemo = false; }
        var car = this.cars[id];
        if (isDemo) {
            var paths = [
                { pos: car.attr().pos },
                { pos: [x, y] }
            ];
            car.animate(paths, {
                iterations: Infinity,
                direction: 'alternate',
                duration: durationSecond * 1000
            });
        }
        car.transition(durationSecond).attr({ pos: [x, y] });
    };
    MapCars.prototype.movePoints = function (id, points, durationSecond) {
        if (durationSecond === void 0) { durationSecond = 2; }
        var car = this.cars[id];
        car.animate(points.map(function (v, i) {
            return { pos: v };
        }), {
            iterations: Infinity,
            direction: 'alternate',
            duration: durationSecond * 1000
        });
    };
    MapCars.prototype.moveOffset = function (id, _a, durationSecond) {
        var x = _a[0], y = _a[1];
        if (durationSecond === void 0) { durationSecond = 2000; }
        var car = this.cars[id];
        var srcX = car.attr("x");
        var srcY = car.attr("y");
        this.carAnimates[id] = this.cars[id].transition(durationSecond).attr({ pos: [srcX + x, srcY + y] });
    };
    return MapCars;
}());
