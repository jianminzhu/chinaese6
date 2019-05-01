var ParkMap = /** @class */ (function () {
    function ParkMap(sel, _a, spritejs) {
        if (sel === void 0) { sel = "#container"; }
        var _b = _a === void 0 ? { resolution: [3427, 1920], map: { bottom: bottom, up: up, down: down } } : _a, _c = _b.resolution, _d = _c[0], width = _d === void 0 ? 3427 : _d, _e = _c[1], heigth = _e === void 0 ? 1920 : _e, _f = _b.bg, bg = _f === void 0 ? "images/bg2.png" : _f, _g = _b.map, _h = _g.bottom, bottom = _h === void 0 ? "images/map_bottom.png?v=1" : _h, _j = _g.up, up = _j === void 0 ? "images/map_up.png?v=1" : _j, _k = _g.down, down = _k === void 0 ? "images/map_down.png?v=1" : _k;
        if (spritejs === void 0) { spritejs = window.spritejs; }
        this.spritejs = spritejs;
        this.cars = {};
        this.carAnimates = {};
        try {
            var _l = this.spritejs, Scene = _l.Scene, Sprite = _l.Sprite, Group = _l.Group;
            var scene = new Scene(sel, { resolution: [width, heigth] });
            var layBg = scene.layer();
            layBg.append(new Sprite({ pos: [0, 0], textures: bg }));
            layBg.append(new Sprite({ pos: [371, 158], textures: bottom }));
            var gCars = this.groupCars = new Group({ pos: [371, 158] });
            layBg.append(new Sprite({ pos: [434, 147], textures: down }));
            layBg.append(gCars);
            layBg.append(new Sprite({ pos: [434, 147], textures: up }));
        }
        catch (e) {
        }
    }
    ParkMap.prototype.getCar = function (id) {
        return this.cars[id];
    };
    ParkMap.prototype.addCar = function (_a) {
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
    ParkMap.prototype.genCarImgs = function (img) {
        var prefix = img.replace(/\d{1,2}\.png$/, "");
        var arr = [];
        for (var i = 1; i <= 12; i++) {
            arr.push({ textures: "" + prefix + (i + 1) + ".png" });
        }
        return arr;
    };
    ;
    ParkMap.prototype.move = function (id, _a, durationSecond, isDemo) {
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
    ParkMap.prototype.movePoints = function (id, points, durationSecond) {
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
    ParkMap.prototype.moveOffset = function (id, _a, durationSecond) {
        var x = _a[0], y = _a[1];
        if (durationSecond === void 0) { durationSecond = 2000; }
        var car = this.cars[id];
        var srcX = car.attr("x");
        var srcY = car.attr("y");
        this.carAnimates[id] = this.cars[id].transition(durationSecond).attr({ pos: [srcX + x, srcY + y] });
    };
    return ParkMap;
}());
