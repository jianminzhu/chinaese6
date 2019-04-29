export class RangeUtil {
    public static range(start, end, fun) {
        return Array(end - start + 1).fill(0).map((v, i) => fun(i + start))
    }
}
