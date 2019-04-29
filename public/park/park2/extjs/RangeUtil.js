"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var RangeUtil = /** @class */ (function () {
    function RangeUtil() {
    }
    RangeUtil.range = function (start, end, fun) {
        return Array(end - start + 1).fill(0).map(function (v, i) { return fun(i + start); });
    };
    return RangeUtil;
}());
exports.RangeUtil = RangeUtil;
