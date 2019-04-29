import {RangeUtil} from './RangeUtil';
console.log(RangeUtil.range(1, 11, function (i) {
    return `car${i}.png`;
}));