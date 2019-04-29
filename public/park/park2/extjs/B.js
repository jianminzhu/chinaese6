var RangeUtil = /** @class */ (function () {
    function RangeUtil() {
    }
    RangeUtil.range = function (start, end, fun) {
        return Array(end - start + 1).fill(0).map(function (v, i) {
            try {
                return fun(i + start);
            }
            catch (e) {
            }
        });
    };
    return RangeUtil;
}());
console.log(RangeUtil.range(1, 10, function (i) {
    return "car" + i + ".png";
}));
