import {Car} from "./Car";

export class PD {
    lbg
    cars = {};

    constructor(public id: string, public width: number, public height: number, preloads = [["carimgs/cars.png", "carimgs/cars.json"]]) {
        const {Scene, Sprite} = spritejs
        const scene = new Scene(id, {viewport: ['auto', 'auto'], resolution: [width, height]})
        this.lbg = scene.layer("bg")
        if (preloads) {
            (async function () {
                for (const preload of preloads) {
                    await scene.preload(preload);
                }
            }())
        }
    }

    addCar(carId, x, y, carNamePrefixs = "car") {
        let car = this.cars[carId] = Car.genCarImgs(carNamePrefixs, 0, 11);
        car.attr({
            anchor: [0.5, 0.5],
            pos: [x, y]
        });
        this.lbg.append(car);
        return car;
    }
}
