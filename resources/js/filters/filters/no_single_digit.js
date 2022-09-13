export default function nosingledigit (input, delim) {
    var final = "";
    if(delim != undefined) {
      var r = input.split(delim);
      var ra = [];

      for(var i = 0; i < r.length; i++) {
        var d = parseInt(r[i]);

        if(d == NaN) continue;

        if(d <= 9)
          d = '0' + d;

        ra.push(d);
      }

       for(var i =0; i < ra.length; i++) {
        final += ra[i];

        if(i != ra.length - 1 )
          final += delim;
      }

      return final;
    } else {
      if (input <= 9)
        input = '0' + input;

      return input;
    }
    return null;
}
