String RTC() {
  String h, m, s;
  String res;
  if (hourNow < 10) {
    h = "0" + (String) hourNow;
  }
  else {
    h = (String) hourNow;
  }
  if (minuteNow < 10) {
    m = "0" + (String) minuteNow;
  }
  else {
    m = (String) minuteNow;
  }
  if (secondNow < 10) {
    s = "0" + (String) secondNow;
  }
  else {
    s = (String) secondNow;
  }
  res = h + ":" + m + ":" + s;
  secondNow++;
  if (secondNow == 60) {
    secondNow = 0;
    minuteNow++;
  }
  if (minuteNow == 60) {
    minuteNow = 0;
    hourNow++;
  }
  if (hourNow == 24) {
    hourNow = 0;
  }
  return res;
}

