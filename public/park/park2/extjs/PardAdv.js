var PardAdv = /** @class */ (function () {
    function PardAdv(id, width, heigth) {
        this.id = id;
        this.width = width;
        this.heigth = heigth;
        this.cars = {};
        try {
            var Scene = spritejs.Scene, Sprite = spritejs.Sprite;
            var scene = new Scene(id, { viewport: [width, heigth], resolution: [width, heigth] });
            this.layer = scene.layer();
        }
        catch (e) {
        }
    }
    PardAdv.prototype.getCar = function (id) {
        return this.cars[id];
    };
    PardAdv.prototype.addCar = function (id, x, y, pic) {
        if (x === void 0) { x = 0; }
        if (y === void 0) { y = 0; }
        var Sprite = spritejs.Sprite;
        var car = new Sprite(pic);
        car.attr({
            anchor: [0, 0.3],
            size: [80, 80],
            pos: [x, y],
            borderRadius: 50
        });
        this.cars[id] = car;
        var v = this;
        setTimeout(function () {
            car.animate(v.genCarImgs(pic), {
                duration: 1000,
                iterations: Infinity,
                direction: 'alternate'
            });
        }, 1000 * Math.random());
        this.layer.append(car);
        return car;
    };
    PardAdv.prototype.genCarImgs = function (img) {
        var prefix = img.replace(/\d{1,2}\.png$/, "");
        var arr = [];
        for (var i = 1; i <= 12; i++) {
            arr.push({ textures: "" + prefix + (i + 1) + ".png" });
        }
        return arr;
    };
    ;
    PardAdv.prototype.moveCar = function (id, x, y) {
        this.cars[id].attr({ pos: [x, y] });
    };
    PardAdv.prototype.moveOffset = function (id, x, y) {
        if (x === void 0) { x = 0; }
        if (y === void 0) { y = 0; }
        var car = this.cars[id];
        var srcX = car.attr("x");
        var srcY = car.attr("y");
        car.attr({ pos: [srcX + x, srcY + y] });
    };
    return PardAdv;
}());
