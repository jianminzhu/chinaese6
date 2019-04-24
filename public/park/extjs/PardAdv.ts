class PardAdv{
    layer;
    cars = {};
    constructor(public id,public width,public heigth) {
        try {
            const {Scene, Sprite} = spritejs
            const scene = new Scene(id, {viewport: [width, heigth], resolution: [width, heigth]})
            this.layer = scene.layer()
        } catch (e) {
            alert(e.toString())
        }
    }
    getCar(id) {
        return this.cars[id];
    }
    addCar(id,x=0,y=0,pic='./static/img/car.png') {
        const { Sprite} = spritejs
        const car = new Sprite(pic)
        car.attr({
            anchor: [0, 0.3],
            pos: [x,y],
            borderRadius: 50,
        })
        this.cars[id] = car;
        this.layer.append(car);
        return car;
    }

    moveCar(id, x, y) {
        this.cars[id].attr({ pos: [x, y ]});
    }
    moveOffset(id, x=0, y=0) {
        let car = this.cars[id];
        var srcX =  car.attr("x");
        var srcY =  car.attr("y");
        car.attr({pos: [srcX + x, srcY + y]});
    }

}