export default {
    padZero: function (value, n) {
       return Math.pow(10, n) > value ? ((Math.pow(10, n) + value) + "").substr(1) : value;
    }
}