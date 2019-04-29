import {RangeUtil} from "./RangeUtil";
export class Car {
    static genCarImgs(carNamePrefixs, start = 0, end = 11, duration = 500) {
        return Car.make(RangeUtil.range(start, end, function (i) {
            return `${carNamePrefixs}${i}.png`;
        }), duration)
    }

    static toTextures(imgs) {
        let textures = [];
        for (const img of imgs) {
            textures.push({textures: img});
        }
        return textures;
    }

    static make(carImgs, duration = 500) {
        let {Sprite}=spritejs
        const s = new Sprite(carImgs[0]);
        s.animate(Car.toTextures(carImgs), {
            duration: duration,
            direction: 'alternate',
            iterations: Infinity,
        });
        return s;
    }
}