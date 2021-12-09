class JalaliCal {
  /*
    Converts a Gregorian date to Jalaali.
  */
  toJalaali(gy, gm, gd) {
    return this.d2j(this.g2d(gy, gm, gd))
  }

  /*
    Converts a Jalaali date to Gregorian.
  */
  toGregorian(jy, jm, jd) {
    return this.d2g(this.j2d(jy, jm, jd))
  }

  /*
    Checks whether a Jalaali date is valid or not.
  */
  isValidJalaaliDate(jy, jm, jd) {
    return jy >= -61 && jy <= 3177 &&
      jm >= 1 && jm <= 12 &&
      jd >= 1 && jd <= this.jalaaliMonthLength(jy, jm)
  }

  /*
    Is this a leap year or not?
  */
  isLeapJalaaliYear(jy) {
    return this.jalCal(jy).leap === 0
  }

  /*
    Number of days in a given month in a Jalaali year.
  */
  jalaaliMonthLength(jy, jm) {
    if (jm <= 6) return 31
    if (jm <= 11) return 30
    if (this.isLeapJalaaliYear(jy)) return 30
    return 29
  }

  /*
    This function determines if the Jalaali (Persian) year is
    leap (366-day long) or is the common year (365 days), and
    finds the day in March (Gregorian calendar) of the first
    day of the Jalaali year (jy).

    @param jy Jalaali calendar year (-61 to 3177)
    @return
      leap: number of years since the last leap year (0 to 4)
      gy: Gregorian year of the beginning of Jalaali year
      march: the March day of Farvardin the 1st (1st day of jy)
    @see: http://www.astro.uni.torun.pl/~kb/Papers/EMP/PersianC-EMP.htm
    @see: http://www.fourmilab.ch/documents/calendar/
  */
  jalCal(jy) {
    // Jalaali years starting the 33-year rule.
    var breaks = [-61, 9, 38, 199, 426, 686, 756, 818, 1111, 1181, 1210
      , 1635, 2060, 2097, 2192, 2262, 2324, 2394, 2456, 3178
    ]
      , bl = breaks.length
      , gy = jy + 621
      , leapJ = -14
      , jp = breaks[0]
      , jm
      , jump
      , leap
      , leapG
      , march
      , n
      , i

    if (jy < jp || jy >= breaks[bl - 1])
      throw new Error('Invalid Jalaali year ' + jy)

    // Find the limiting years for the Jalaali year jy.
    for (i = 1; i < bl; i += 1) {
      jm = breaks[i]
      jump = jm - jp
      if (jy < jm)
        break
      leapJ = leapJ + this.div(jump, 33) * 8 + this.div(this.mod(jump, 33), 4)
      jp = jm
    }
    n = jy - jp

    // Find the number of leap years from AD 621 to the beginning
    // of the current Jalaali year in the Persian calendar.
    leapJ = leapJ + this.div(n, 33) * 8 + this.div(this.mod(n, 33) + 3, 4)
    if (this.mod(jump, 33) === 4 && jump - n === 4)
      leapJ += 1

    // And the same in the Gregorian calendar (until the year gy).
    leapG = this.div(gy, 4) - this.div((this.div(gy, 100) + 1) * 3, 4) - 150

    // Determine the Gregorian date of Farvardin the 1st.
    march = 20 + leapJ - leapG

    // Find how many years have passed since the last leap year.
    if (jump - n < 6)
      n = n - jump + this.div(jump + 4, 33) * 33
    leap = this.mod(this.mod(n + 1, 33) - 1, 4)
    if (leap === -1) {
      leap = 4
    }

    return {
      leap: leap
      , gy: gy
      , march: march
    }
  }

  /*
    Converts a date of the Jalaali calendar to the Julian Day number.

    @param jy Jalaali year (1 to 3100)
    @param jm Jalaali month (1 to 12)
    @param jd Jalaali day (1 to 29/31)
    @return Julian Day number
  */
  j2d(jy, jm, jd) {
    var r = this.jalCal(jy)
    return this.g2d(r.gy, 3, r.march) + (jm - 1) * 31 - this.div(jm, 7) * (jm - 7) + jd - 1
  }

  /*
    Converts the Julian Day number to a date in the Jalaali calendar.

    @param jdn Julian Day number
    @return
      jy: Jalaali year (1 to 3100)
      jm: Jalaali month (1 to 12)
      jd: Jalaali day (1 to 29/31)
  */
  d2j(jdn) {
    var gy = this.d2g(jdn).gy // Calculate Gregorian year (gy).
      , jy = gy - 621
      , r = this.jalCal(jy)
      , jdn1f = this.g2d(gy, 3, r.march)
      , jd
      , jm
      , k

    // Find number of days that passed since 1 Farvardin.
    k = jdn - jdn1f
    if (k >= 0) {
      if (k <= 185) {
        // The first 6 months.
        jm = 1 + this.div(k, 31)
        jd = this.mod(k, 31) + 1
        return {
          jy: jy
          , jm: jm
          , jd: jd
        }
      } else {
        // The remaining months.
        k -= 186
      }
    } else {
      // Previous Jalaali year.
      jy -= 1
      k += 179
      if (r.leap === 1)
        k += 1
    }
    jm = 7 + this.div(k, 30)
    jd = this.mod(k, 30) + 1
    return {
      jy: jy
      , jm: jm
      , jd: jd
    }
  }

  /*
    Calculates the Julian Day number from Gregorian or Julian
    calendar dates. This integer number corresponds to the noon of
    the date (i.e. 12 hours of Universal Time).
    The procedure was tested to be good since 1 March, -100100 (of both
    calendars) up to a few million years into the future.

    @param gy Calendar year (years BC numbered 0, -1, -2, ...)
    @param gm Calendar month (1 to 12)
    @param gd Calendar day of the month (1 to 28/29/30/31)
    @return Julian Day number
  */
  g2d(gy, gm, gd) {
    var d = this.div((gy + this.div(gm - 8, 6) + 100100) * 1461, 4)
      + this.div(153 * this.mod(gm + 9, 12) + 2, 5)
      + gd - 34840408
    d = d - this.div(this.div(gy + 100100 + this.div(gm - 8, 6), 100) * 3, 4) + 752
    return d
  }

  /*
    Calculates Gregorian and Julian calendar dates from the Julian Day number
    (jdn) for the period since jdn=-34839655 (i.e. the year -100100 of both
    calendars) to some millions years ahead of the present.

    @param jdn Julian Day number
    @return
      gy: Calendar year (years BC numbered 0, -1, -2, ...)
      gm: Calendar month (1 to 12)
      gd: Calendar day of the month M (1 to 28/29/30/31)
  */
  d2g(jdn) {
    var j
      , i
      , gd
      , gm
      , gy
    j = 4 * jdn + 139361631
    j = j + this.div(this.div(4 * jdn + 183187720, 146097) * 3, 4) * 4 - 3908
    i = this.div(this.mod(j, 1461), 4) * 5 + 308
    gd = this.div(this.mod(i, 153), 5) + 1
    gm = this.mod(this.div(i, 153), 12) + 1
    gy = this.div(j, 1461) - 100100 + this.div(8 - gm, 6)
    return {
      gy: gy
      , gm: gm
      , gd: gd
    }
  }

  /*
    Utility helper functions.
  */

  div(a, b) {
    return ~~(a / b)
  }

  mod(a, b) {
    return a - ~~(a / b) * b
  }
}

class HijriCal {
    constructor() {
        this.ummalquraData = [28607, 28636, 28665, 28695, 28724, 28754, 28783, 28813, 28843, 28872, 28901, 28931, 28960, 28990, 29019, 29049, 29078, 29108, 29137, 29167,
            29196, 29226, 29255, 29285, 29315, 29345, 29375, 29404, 29434, 29463, 29492, 29522, 29551, 29580, 29610, 29640, 29669, 29699, 29729, 29759,
            29788, 29818, 29847, 29876, 29906, 29935, 29964, 29994, 30023, 30053, 30082, 30112, 30141, 30171, 30200, 30230, 30259, 30289, 30318, 30348,
            30378, 30408, 30437, 30467, 30496, 30526, 30555, 30585, 30614, 30644, 30673, 30703, 30732, 30762, 30791, 30821, 30850, 30880, 30909, 30939,
            30968, 30998, 31027, 31057, 31086, 31116, 31145, 31175, 31204, 31234, 31263, 31293, 31322, 31352, 31381, 31411, 31441, 31471, 31500, 31530,
            31559, 31589, 31618, 31648, 31676, 31706, 31736, 31766, 31795, 31825, 31854, 31884, 31913, 31943, 31972, 32002, 32031, 32061, 32090, 32120,
            32150, 32180, 32209, 32239, 32268, 32298, 32327, 32357, 32386, 32416, 32445, 32475, 32504, 32534, 32563, 32593, 32622, 32652, 32681, 32711,
            32740, 32770, 32799, 32829, 32858, 32888, 32917, 32947, 32976, 33006, 33035, 33065, 33094, 33124, 33153, 33183, 33213, 33243, 33272, 33302,
            33331, 33361, 33390, 33420, 33450, 33479, 33509, 33539, 33568, 33598, 33627, 33657, 33686, 33716, 33745, 33775, 33804, 33834, 33863, 33893,
            33922, 33952, 33981, 34011, 34040, 34069, 34099, 34128, 34158, 34187, 34217, 34247, 34277, 34306, 34336, 34365, 34395, 34424, 34454, 34483,
            34512, 34542, 34571, 34601, 34631, 34660, 34690, 34719, 34749, 34778, 34808, 34837, 34867, 34896, 34926, 34955, 34985, 35015, 35044, 35074,
            35103, 35133, 35162, 35192, 35222, 35251, 35280, 35310, 35340, 35370, 35399, 35429, 35458, 35488, 35517, 35547, 35576, 35605, 35635, 35665,
            35694, 35723, 35753, 35782, 35811, 35841, 35871, 35901, 35930, 35960, 35989, 36019, 36048, 36078, 36107, 36136, 36166, 36195, 36225, 36254,
            36284, 36314, 36343, 36373, 36403, 36433, 36462, 36492, 36521, 36551, 36580, 36610, 36639, 36669, 36698, 36728, 36757, 36786, 36816, 36845,
            36875, 36904, 36934, 36963, 36993, 37022, 37052, 37081, 37111, 37141, 37170, 37200, 37229, 37259, 37288, 37318, 37347, 37377, 37406, 37436,
            37465, 37495, 37524, 37554, 37584, 37613, 37643, 37672, 37701, 37731, 37760, 37790, 37819, 37849, 37878, 37908, 37938, 37967, 37997, 38027,
            38056, 38085, 38115, 38144, 38174, 38203, 38233, 38262, 38292, 38322, 38351, 38381, 38410, 38440, 38469, 38499, 38528, 38558, 38587, 38617,
            38646, 38676, 38705, 38735, 38764, 38794, 38823, 38853, 38882, 38912, 38941, 38971, 39001, 39030, 39059, 39089, 39118, 39148, 39178, 39208,
            39237, 39267, 39297, 39326, 39355, 39385, 39414, 39444, 39473, 39503, 39532, 39562, 39592, 39621, 39650, 39680, 39709, 39739, 39768, 39798,
            39827, 39857, 39886, 39916, 39946, 39975, 40005, 40035, 40064, 40094, 40123, 40153, 40182, 40212, 40241, 40271, 40300, 40330, 40359, 40389,
            40418, 40448, 40477, 40507, 40536, 40566, 40595, 40625, 40655, 40685, 40714, 40744, 40773, 40803, 40832, 40862, 40892, 40921, 40951, 40980,
            41009, 41039, 41068, 41098, 41127, 41157, 41186, 41216, 41245, 41275, 41304, 41334, 41364, 41393, 41422, 41452, 41481, 41511, 41540, 41570,
            41599, 41629, 41658, 41688, 41718, 41748, 41777, 41807, 41836, 41865, 41894, 41924, 41953, 41983, 42012, 42042, 42072, 42102, 42131, 42161,
            42190, 42220, 42249, 42279, 42308, 42337, 42367, 42397, 42426, 42456, 42485, 42515, 42545, 42574, 42604, 42633, 42662, 42692, 42721, 42751,
            42780, 42810, 42839, 42869, 42899, 42929, 42958, 42988, 43017, 43046, 43076, 43105, 43135, 43164, 43194, 43223, 43253, 43283, 43312, 43342,
            43371, 43401, 43430, 43460, 43489, 43519, 43548, 43578, 43607, 43637, 43666, 43696, 43726, 43755, 43785, 43814, 43844, 43873, 43903, 43932,
            43962, 43991, 44021, 44050, 44080, 44109, 44139, 44169, 44198, 44228, 44258, 44287, 44317, 44346, 44375, 44405, 44434, 44464, 44493, 44523,
            44553, 44582, 44612, 44641, 44671, 44700, 44730, 44759, 44788, 44818, 44847, 44877, 44906, 44936, 44966, 44996, 45025, 45055, 45084, 45114,
            45143, 45172, 45202, 45231, 45261, 45290, 45320, 45350, 45380, 45409, 45439, 45468, 45498, 45527, 45556, 45586, 45615, 45644, 45674, 45704,
            45733, 45763, 45793, 45823, 45852, 45882, 45911, 45940, 45970, 45999, 46028, 46058, 46088, 46117, 46147, 46177, 46206, 46236, 46265, 46295,
            46324, 46354, 46383, 46413, 46442, 46472, 46501, 46531, 46560, 46590, 46620, 46649, 46679, 46708, 46738, 46767, 46797, 46826, 46856, 46885,
            46915, 46944, 46974, 47003, 47033, 47063, 47092, 47122, 47151, 47181, 47210, 47240, 47269, 47298, 47328, 47357, 47387, 47417, 47446, 47476,
            47506, 47535, 47565, 47594, 47624, 47653, 47682, 47712, 47741, 47771, 47800, 47830, 47860, 47890, 47919, 47949, 47978, 48008, 48037, 48066,
            48096, 48125, 48155, 48184, 48214, 48244, 48273, 48303, 48333, 48362, 48392, 48421, 48450, 48480, 48509, 48538, 48568, 48598, 48627, 48657,
            48687, 48717, 48746, 48776, 48805, 48834, 48864, 48893, 48922, 48952, 48982, 49011, 49041, 49071, 49100, 49130, 49160, 49189, 49218, 49248,
            49277, 49306, 49336, 49365, 49395, 49425, 49455, 49484, 49514, 49543, 49573, 49602, 49632, 49661, 49690, 49720, 49749, 49779, 49809, 49838,
            49868, 49898, 49927, 49957, 49986, 50016, 50045, 50075, 50104, 50133, 50163, 50192, 50222, 50252, 50281, 50311, 50340, 50370, 50400, 50429,
            50459, 50488, 50518, 50547, 50576, 50606, 50635, 50665, 50694, 50724, 50754, 50784, 50813, 50843, 50872, 50902, 50931, 50960, 50990, 51019,
            51049, 51078, 51108, 51138, 51167, 51197, 51227, 51256, 51286, 51315, 51345, 51374, 51403, 51433, 51462, 51492, 51522, 51552, 51582, 51611,
            51641, 51670, 51699, 51729, 51758, 51787, 51816, 51846, 51876, 51906, 51936, 51965, 51995, 52025, 52054, 52083, 52113, 52142, 52171, 52200,
            52230, 52260, 52290, 52319, 52349, 52379, 52408, 52438, 52467, 52497, 52526, 52555, 52585, 52614, 52644, 52673, 52703, 52733, 52762, 52792,
            52822, 52851, 52881, 52910, 52939, 52969, 52998, 53028, 53057, 53087, 53116, 53146, 53176, 53205, 53235, 53264, 53294, 53324, 53353, 53383,
            53412, 53441, 53471, 53500, 53530, 53559, 53589, 53619, 53648, 53678, 53708, 53737, 53767, 53796, 53825, 53855, 53884, 53913, 53943, 53973,
            54003, 54032, 54062, 54092, 54121, 54151, 54180, 54209, 54239, 54268, 54297, 54327, 54357, 54387, 54416, 54446, 54476, 54505, 54535, 54564,
            54593, 54623, 54652, 54681, 54711, 54741, 54770, 54800, 54830, 54859, 54889, 54919, 54948, 54977, 55007, 55036, 55066, 55095, 55125, 55154,
            55184, 55213, 55243, 55273, 55302, 55332, 55361, 55391, 55420, 55450, 55479, 55508, 55538, 55567, 55597, 55627, 55657, 55686, 55716, 55745,
            55775, 55804, 55834, 55863, 55892, 55922, 55951, 55981, 56011, 56040, 56070, 56100, 56129, 56159, 56188, 56218, 56247, 56276, 56306, 56335,
            56365, 56394, 56424, 56454, 56483, 56513, 56543, 56572, 56601, 56631, 56660, 56690, 56719, 56749, 56778, 56808, 56837, 56867, 56897, 56926,
            56956, 56985, 57015, 57044, 57074, 57103, 57133, 57162, 57192, 57221, 57251, 57280, 57310, 57340, 57369, 57399, 57429, 57458, 57487, 57517,
            57546, 57576, 57605, 57634, 57664, 57694, 57723, 57753, 57783, 57813, 57842, 57871, 57901, 57930, 57959, 57989, 58018, 58048, 58077, 58107,
            58137, 58167, 58196, 58226, 58255, 58285, 58314, 58343, 58373, 58402, 58432, 58461, 58491, 58521, 58551, 58580, 58610, 58639, 58669, 58698,
            58727, 58757, 58786, 58816, 58845, 58875, 58905, 58934, 58964, 58994, 59023, 59053, 59082, 59111, 59141, 59170, 59200, 59229, 59259, 59288,
            59318, 59348, 59377, 59407, 59436, 59466, 59495, 59525, 59554, 59584, 59613, 59643, 59672, 59702, 59731, 59761, 59791, 59820, 59850, 59879,
            59909, 59939, 59968, 59997, 60027, 60056, 60086, 60115, 60145, 60174, 60204, 60234, 60264, 60293, 60323, 60352, 60381, 60411, 60440, 60469,
            60499, 60528, 60558, 60588, 60618, 60648, 60677, 60707, 60736, 60765, 60795, 60824, 60853, 60883, 60912, 60942, 60972, 61002, 61031, 61061,
            61090, 61120, 61149, 61179, 61208, 61237, 61267, 61296, 61326, 61356, 61385, 61415, 61445, 61474, 61504, 61533, 61563, 61592, 61621, 61651,
            61680, 61710, 61739, 61769, 61799, 61828, 61858, 61888, 61917, 61947, 61976, 62006, 62035, 62064, 62094, 62123, 62153, 62182, 62212, 62242,
            62271, 62301, 62331, 62360, 62390, 62419, 62448, 62478, 62507, 62537, 62566, 62596, 62625, 62655, 62685, 62715, 62744, 62774, 62803, 62832,
            62862, 62891, 62921, 62950, 62980, 63009, 63039, 63069, 63099, 63128, 63157, 63187, 63216, 63246, 63275, 63305, 63334, 63363, 63393, 63423,
            63453, 63482, 63512, 63541, 63571, 63600, 63630, 63659, 63689, 63718, 63747, 63777, 63807, 63836, 63866, 63895, 63925, 63955, 63984, 64014,
            64043, 64073, 64102, 64131, 64161, 64190, 64220, 64249, 64279, 64309, 64339, 64368, 64398, 64427, 64457, 64486, 64515, 64545, 64574, 64603,
            64633, 64663, 64692, 64722, 64752, 64782, 64811, 64841, 64870, 64899, 64929, 64958, 64987, 65017, 65047, 65076, 65106, 65136, 65166, 65195,
            65225, 65254, 65283, 65313, 65342, 65371, 65401, 65431, 65460, 65490, 65520, 65549, 65579, 65608, 65638, 65667, 65697, 65726, 65755, 65785,
            65815, 65844, 65874, 65903, 65933, 65963, 65992, 66022, 66051, 66081, 66110, 66140, 66169, 66199, 66228, 66258, 66287, 66317, 66346, 66376,
            66405, 66435, 66465, 66494, 66524, 66553, 66583, 66612, 66641, 66671, 66700, 66730, 66760, 66789, 66819, 66849, 66878, 66908, 66937, 66967,
            66996, 67025, 67055, 67084, 67114, 67143, 67173, 67203, 67233, 67262, 67292, 67321, 67351, 67380, 67409, 67439, 67468, 67497, 67527, 67557,
            67587, 67617, 67646, 67676, 67705, 67735, 67764, 67793, 67823, 67852, 67882, 67911, 67941, 67971, 68000, 68030, 68060, 68089, 68119, 68148,
            68177, 68207, 68236, 68266, 68295, 68325, 68354, 68384, 68414, 68443, 68473, 68502, 68532, 68561, 68591, 68620, 68650, 68679, 68708, 68738,
            68768, 68797, 68827, 68857, 68886, 68916, 68946, 68975, 69004, 69034, 69063, 69092, 69122, 69152, 69181, 69211, 69240, 69270, 69300, 69330,
            69359, 69388, 69418, 69447, 69476, 69506, 69535, 69565, 69595, 69624, 69654, 69684, 69713, 69743, 69772, 69802, 69831, 69861, 69890, 69919,
            69949, 69978, 70008, 70038, 70067, 70097, 70126, 70156, 70186, 70215, 70245, 70274, 70303, 70333, 70362, 70392, 70421, 70451, 70481, 70510,
            70540, 70570, 70599, 70629, 70658, 70687, 70717, 70746, 70776, 70805, 70835, 70864, 70894, 70924, 70954, 70983, 71013, 71042, 71071, 71101,
            71130, 71159, 71189, 71218, 71248, 71278, 71308, 71337, 71367, 71397, 71426, 71455, 71485, 71514, 71543, 71573, 71602, 71632, 71662, 71691,
            71721, 71751, 71781, 71810, 71839, 71869, 71898, 71927, 71957, 71986, 72016, 72046, 72075, 72105, 72135, 72164, 72194, 72223, 72253, 72282,
            72311, 72341, 72370, 72400, 72429, 72459, 72489, 72518, 72548, 72577, 72607, 72637, 72666, 72695, 72725, 72754, 72784, 72813, 72843, 72872,
            72902, 72931, 72961, 72991, 73020, 73050, 73080, 73109, 73139, 73168, 73197, 73227, 73256, 73286, 73315, 73345, 73375, 73404, 73434, 73464,
            73493, 73523, 73552, 73581, 73611, 73640, 73669, 73699, 73729, 73758, 73788, 73818, 73848, 73877, 73907, 73936, 73965, 73995, 74024, 74053,
            74083, 74113, 74142, 74172, 74202, 74231, 74261, 74291, 74320, 74349, 74379, 74408, 74437, 74467, 74497, 74526, 74556, 74586, 74615, 74645,
            74675, 74704, 74733, 74763, 74792, 74822, 74851, 74881, 74910, 74940, 74969, 74999, 75029, 75058, 75088, 75117, 75147, 75176, 75206, 75235,
            75264, 75294, 75323, 75353, 75383, 75412, 75442, 75472, 75501, 75531, 75560, 75590, 75619, 75648, 75678, 75707, 75737, 75766, 75796, 75826,
            75856, 75885, 75915, 75944, 75974, 76003, 76032, 76062, 76091, 76121, 76150, 76180, 76210, 76239, 76269, 76299, 76328, 76358, 76387, 76416,
            76446, 76475, 76505, 76534, 76564, 76593, 76623, 76653, 76682, 76712, 76741, 76771, 76801, 76830, 76859, 76889, 76918, 76948, 76977, 77007,
            77036, 77066, 77096, 77125, 77155, 77185, 77214, 77243, 77273, 77302, 77332, 77361, 77390, 77420, 77450, 77479, 77509, 77539, 77569, 77598,
            77627, 77657, 77686, 77715, 77745, 77774, 77804, 77833, 77863, 77893, 77923, 77952, 77982, 78011, 78041, 78070, 78099, 78129, 78158, 78188,
            78217, 78247, 78277, 78307, 78336, 78366, 78395, 78425, 78454, 78483, 78513, 78542, 78572, 78601, 78631, 78661, 78690, 78720, 78750, 78779,
            78808, 78838, 78867, 78897, 78926, 78956, 78985, 79015, 79044, 79074, 79104, 79133, 79163, 79192, 79222, 79251, 79281, 79310, 79340, 79369,
            79399, 79428, 79458, 79487, 79517, 79546, 79576, 79606, 79635, 79665, 79695, 79724, 79753, 79783, 79812, 79841, 79871, 79900, 79930, 79960,
            79990];
    }

    toHijri(gy, gm, gd) {
        var h = this.d2h(this.g2d(gy, gm + 1, gd))
        h.hm -= 1
        return h
    }

    toGregorian(hy, hm, hd) {
        var g = this.d2g(this.h2d(hy, hm + 1, hd))
        g.gm -= 1
        return g
    }

    /*
    Converts a date of the Hijri calendar to the Julian Day number.

    @param hy Hijri year (1356 to 1500)
    @param hm Hijri month (1 to 12)
    @param hd Hijri day (1 to 29/30)
    @return Julian Day number
    */
    h2d(hy, hm, hd) {
        var i = this.getNewMoonMJDNIndex(hy, hm),
            mjdn = hd + this.ummalquraData[i - 1] - 1,
            jdn = mjdn + 2400000;
        return jdn
    }

    /*
    Converts the Julian Day number to a date in the Hijri calendar.

    @param jdn Julian Day number
    @return
      hy: Hijri year (1356 to 1500)
      hm: Hijri month (1 to 12)
      hd: Hijri day (1 to 29/30)
    */
    d2h(jdn) {
        var mjdn = jdn - 2400000,
            i = this.getNewMoonMJDNIndexByJDN(mjdn),
            totalMonths = i + 16260,
            cYears = Math.floor((totalMonths - 1) / 12),
            hy = cYears + 1,
            hm = totalMonths - 12 * cYears,
            hd = mjdn - this.ummalquraData[i - 1] + 1

        return {
            hy: hy,
            hm: hm,
            hd: hd
        }
    }

    /*
    Calculates the Julian Day number from Gregorian or Julian
    calendar dates. This integer number corresponds to the noon of
    the date (i.e. 12 hours of Universal Time).
    The procedure was tested to be good since 1 March, -100100 (of both
    calendars) up to a few million years into the future.

    @param gy Calendar year (years BC numbered 0, -1, -2, ...)
    @param gm Calendar month (1 to 12)
    @param gd Calendar day of the month (1 to 28/29/30/31)
    @return Julian Day number
    */
    g2d(gy, gm, gd) {
        var d = this.div((gy + this.div(gm - 8, 6) + 100100) * 1461, 4) + this.div(153 * this.mod(gm + 9, 12) + 2, 5) + gd - 34840408
        d = d - this.div(this.div(gy + 100100 + this.div(gm - 8, 6), 100) * 3, 4) + 752
        return d
    }

    /*
    Calculates Gregorian and Julian calendar dates from the Julian Day number
    (hdn) for the period since jdn=-34839655 (i.e. the year -100100 of both
    calendars) to some millions years ahead of the present.

    @param jdn Julian Day number
    @return
      gy: Calendar year (years BC numbered 0, -1, -2, ...)
      gm: Calendar month (1 to 12)
      gd: Calendar day of the month M (1 to 28/29/30/31)
    */
    d2g(jdn) {
        var j, i, gd, gm, gy
        j = 4 * jdn + 139361631
        j = j + this.div(this.div(4 * jdn + 183187720, 146097) * 3, 4) * 4 - 3908
        i = this.div(this.mod(j, 1461), 4) * 5 + 308
        gd = this.div(this.mod(i, 153), 5) + 1
        gm = this.mod(this.div(i, 153), 12) + 1
        gy = this.div(j, 1461) - 100100 + this.div(8 - gm, 6)
        return {
            gy: gy,
            gm: gm,
            gd: gd
        }
    }

    /*
    Returns the index of the modified Julian day number of the new moon
    by the given year and month

    @param hy: Hijri year (1356 to 1500)
    @param hm: Hijri month (1 to 12)
    @return
        i: the index of the new moon in modified Julian day number.
    */
    getNewMoonMJDNIndex(hy, hm) {
        var cYears = hy - 1,
            totalMonths = (cYears * 12) + 1 + (hm - 1),
            i = totalMonths - 16260
        return i
    }

    /*
    Returns the nearest new moon

    @param jdn Julian Day number
    @return
      i: the index of a modified Julian day number.
    */
    getNewMoonMJDNIndexByJDN(mjdn) {
        for (var i = 0; i < this.ummalquraData.length; i = i + 1) {
            if (this.ummalquraData[i] > mjdn)
                return i
        }
    }

    /*
    Utility helper functions.
    */
    div(a, b) {
        return ~~(a / b)
    }

    mod(a, b) {
        return a - ~~(a / b) * b
    }
}

/* sets table and calendar defaults */
function InitCalendar(table) {
    let element = table.find('.fc-table');
    if (element.length === 0) {
      insertElement(table)
      element = $('.fc-table');
    }

    var jalaliDate = getTOday();
    _year = jalaliDate.jy;
    _month = jalaliDate.jm - 1;
    $(".fc-year").html(_year);
    $(".fc-month").attr('data-id' , _month);
    $(".fc-month").html(getMonthName(_month));
    UpdateCal(element, _year, _month, _day);

}

function getTOday(){
    var today = new Date();
    var year = today.getFullYear();
    var month = today.getMonth() + 1;
    var day = today.getDate();
    return _jlcal.toJalaali(year, month, day);
}
function getTOday_marker(table){
    table.find(".fc-today").removeClass('fc-today');
    var jalaliDate = getTOday();
    let today_marker = jalaliDate.jy + '-' + jalaliDate.jm + '-' + jalaliDate.jd;
    table.find($("td[data-jl='"+today_marker+"']")).addClass('fc-today');
}

/* updates table values */
function UpdateCal(table, year, month, day) {
    var today, dayOfWeek, daysInMonth, count = 1;
    var tb = $(table), td;
    var j2g, g2h;
    var now = new Date();

    j2g = _jlcal.toGregorian(year, month + 1, 1);
    today = new Date(j2g.gy, j2g.gm - 1, j2g.gd);
    dayOfWeek = today.getDay();
    dayOfWeek = (dayOfWeek == 6) ? 0 : dayOfWeek + 1;
    daysInMonth = _jlcal.jalaaliMonthLength(year, month);

    if ((dayOfWeek == 5 && daysInMonth > 30) || (dayOfWeek == 6 && daysInMonth >= 30)) {
        $(tb).find("tr:last").removeClass("hidden");
    }
    else {
        $(tb).find("tr:last").addClass("hidden");
    }

    $(tb).find("td").text("")
        .removeAttr("data-jl")
        .removeAttr("data-gr")
        .removeAttr("data-hj")
        .removeClass("dayoff");
    td = $(tb).find("td");

    for (var index = dayOfWeek; index < daysInMonth + dayOfWeek; index++) {
        var element = td[index];
        j2g = _jlcal.toGregorian(year, month + 1, count);
        g2h = _hjcal.toHijri(j2g.gy, j2g.gm - 1, j2g.gd);
        today = new Date(j2g.gy, j2g.gm - 1, j2g.gd);

        if (gd2Txt(today.getDay()) == "Fir" || isoff("jl", month, count) || isoff("hj", g2h.hm, g2h.hd)) {
            $(element).addClass("dayoff");
        }
        $(element).attr("data-jl", (year + "-" + (month + 1) + "-" + count));
        $(element).attr("data-gr", (today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate()));
        $(element).attr("data-hj", (g2h.hy + "-" + (g2h.hm + 1) + "-" + g2h.hd));
        $(element).html(
            "<b>" + count + "</b>" + "\n"/* +
            "<span>" +
            "<div class='font-fa tdinfo'>" +
            g2h.hy + " " + hm2Txt(g2h.hm) + " " + g2h.hd +
            "</div>" +
            "<div class='font-eng tdinfo'>" +
            gd2Txt(today.getDay()) + " " +
            gm2Txt(today.getMonth()) + " " +
            today.getDate() + " " +
            today.getFullYear() +
            "</div></span>"*/
        );
        count++;
    }

    getTOday_marker(table);
}

/* helpers */
function gd2Txt(day) {
    switch (day) {
        case 0:
            return "Sun";
            break;
        case 1:
            return "Mon";
            break;
        case 2:
            return "Tue";
            break;
        case 3:
            return "Wed";
            break;
        case 4:
            return "Thu";
            break;
        case 5:
            return "Fir";
            break;
        case 6:
            return "Sat";
            break;
        default:
            return "خطا";
            break;
    }
}
function gm2Txt(mon) {
    switch (mon) {
        case 0:
            return "Jan";
            break;
        case 1:
            return "Feb";
            break;
        case 2:
            return "Mar";
            break;
        case 3:
            return "Apr";
            break;
        case 4:
            return "May";
            break;
        case 5:
            return "Jun";
            break;
        case 6:
            return "Jul";
            break;
        case 7:
            return "Aug";
            break;
        case 8:
            return "Sep";
            break;
        case 9:
            return "Oct";
            break;
        case 10:
            return "Nov";
            break;
        case 11:
            return "Dec";
            break;
        default:
            return "خطا";
            break;
    }
}
function hm2Txt(mon) {
    switch (mon) {
        case 0:
            return "محرم";
            break;
        case 1:
            return "صفر";
            break;
        case 2:
            return "ربیع الاول";
            break;
        case 3:
            return "ربیع الثانی";
            break;
        case 4:
            return "جمادی الاول";
            break;
        case 5:
            return "جمادی الثانی";
            break;
        case 6:
            return "رجب";
            break;
        case 7:
            return "شعبان";
            break;
        case 8:
            return "رمضان";
            break;
        case 9:
            return "شوال";
            break;
        case 10:
            return "ذی القعده";
            break;
        case 11:
            return "ذی الحجه";
            break;
        default:
            return "خطا";
            break;
    }
}
function isoff(calType, mon, day) {
    var jlDayoff = [
        "1/1", "1/2", "1/3", "1/4", "1/12", "1/13",
        "3/14", "3/15", "11/22", "12/29"];
    var hjDayoff = [];
        /*["1/9", "1/10", "2/20", "2/28", "2/30", "3/17", "6/3", "7/13",
        "7/27", "8/15", "9/21", "10/1", "10/2", "10/25", "12/10", "12/18"];*/
    mon += 1;
    if (calType == "jl") {
        if (jlDayoff.indexOf(mon + "/" + day) != -1)
            return true;
        else
            return false;
    }
    if (calType == "hj") {
        if (hjDayoff.indexOf(mon + "/" + day) != -1)
            return true
        else
            return false;
    }
}
function getMonthName(id) {
  let response = null;
  switch (id) {
    case 0 : response = 'فروردین'; break;
    case 1 : response =  'اردیبهشت'; break;
    case 2 : response =  'خرداد'; break;
    case 3 : response =  'تیر'; break;
    case 4 : response =  'مرداد'; break;
    case 5 : response =  'شهریور'; break;
    case 6 : response =  'مهر'; break;
    case 7 : response =  'آبان'; break;
    case 8 : response =  'آذر'; break;
    case 9 : response =  'دی'; break;
    case 10 : response =  'بهمن'; break;
    case 11 : response =  'اسفند'; break;
  }
  return response;
}
function insertElement(table) {
  table.append('<div class="table-responsive"><table class="table fc-table">\n' +
      '                <caption class="row ">\n' +
      '                    <div class="col-3 text-center picker-wrapper year-picker d-flex align-items-center">\n' +
      '                        <div class="picker-inner  ">\n' +
      '                            <button class="next-year bd-next" type="button" title="سال بعدی" data-toggle="tooltip"><span style="display: none;">بعدی</span></button>\n' +
      '                            <label class="fc-year"></label>\n' +
      '                            <button class="previous-year bd-prev" type="button" title="سال قبلی" data-toggle="tooltip"><span style="display: none;">قبلی</span></button>\n' +
      '                        </div>\n' +
      '                    </div>\n' +
      '                    <div class="col-9 text-center picker-wrapper month-picker ">\n' +
      '                        <div class="picker-inner ">\n' +
      '                            <button class="next-month bd-next" type="button" title="سال بعدی" data-toggle="tooltip"><span style="display: none;">بعدی</span></button>\n' +
      '                            <label class="fc-month" data-id=""></label>\n' +
      '                            <button class="previous-month bd-prev" type="button" title="سال قبلی" data-toggle="tooltip"><span style="display: none;">قبلی</span></button>\n' +
      '                        </div>\n' +
      '                    </div>\n' +
      '                </caption>\n' +
      '                <tr>\n' +
      '                    <th>شنبه</th>\n' +
      '                    <th>یکشنبه</th>\n' +
      '                    <th>دوشنبه</th>\n' +
      '                    <th>سه شنبه</th>\n' +
      '                    <th>چهارشنبه</th>\n' +
      '                    <th>پنچشنبه</th>\n' +
      '                    <th>جمعه</th>\n' +
      '                </tr>\n' +
      '                <tr>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                </tr>\n' +
      '                <tr>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                </tr>\n' +
      '                <tr>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                </tr>\n' +
      '                <tr>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                </tr>\n' +
      '                <tr>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                </tr>\n' +
      '                <tr>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                    <td></td>\n' +
      '                </tr>\n' +
      '            </table></div>');
}

var _year;
var _month;
var _day = 1;
var _currDay;
var _jlcal = new JalaliCal();
var _hjcal = new HijriCal();

/* handle events */
$('body').on('click', ".next-year", function () {
  _year = parseInt(parseInt($(".fc-year").html()) + 1);
  $(".fc-year").html(_year);
  UpdateCal($(this).closest("table"), _year, _month, _day);
});
$('body').on('click', ".previous-year", function () {
  _year = parseInt(parseInt($(".fc-year").html()) - 1);
  $(".fc-year").html(_year);
  UpdateCal($(this).closest("table"), _year, _month, _day);
});

$('body').on('click', ".next-month", function () {
  if(parseInt($(".fc-month").attr('data-id')) < 11){
    _month = parseInt(parseInt($(".fc-month").attr('data-id')) + 1);
    $(".fc-month").attr('data-id' , _month);
    $(".fc-month").html(getMonthName(_month));
  } else {
    _month = 0;
    $(".fc-month").attr('data-id' , _month);
    $(".fc-month").html(getMonthName(_month));
    _year = parseInt(_year) + 1;
    $(".fc-year").html(_year);
  }
  UpdateCal($(this).closest("table"), _year, _month, _day);
});
$('body').on('click', ".previous-month", function () {
  if(parseInt($(".fc-month").attr('data-id')) > 0){
    _month = parseInt(parseInt($(".fc-month").attr('data-id')) - 1);
    $(".fc-month").attr('data-id' , _month);
    $(".fc-month").html(getMonthName(_month));
  } else {
    _month = 11;
    $(".fc-month").attr('data-id' , _month);
    $(".fc-month").html(getMonthName(_month));
    _year = parseInt(_year) - 1;
    $(".fc-year").html(_year);
  }
  UpdateCal($(this).closest("table"), _year, _month, _day);
});

$('body').on("click", ".fc-table td", function (e) {
    $tb = $(this).closest("table");
    $(".fc-table td span").popover('dispose');
    if ($(this).attr("data-jl") && $(this).attr("data-gr")) {
        var title = "", content = "";
        _currDay = $(this);
        var jl = $(this).attr("data-jl").split("-");
        var wc = $(this).attr("data-gr").split("-");
        var hj = $(this).attr("data-hj").split("-");
        $.ajax({
            url: "/_manager/dailyQuery/report/" +
            jl[0] + "-" + jl[1] + "-" + jl[2],
            type: "GET",
            dataType: 'json',
            // date: { limit: 3 },
            success: function (data) {
                let flag = true;
                let content = '';
                $.each(data, function (key, item) {
                    let status = '<span class="text-danger float-end"> هنوز ثبت نشده </span>';
                    if (item.status) status = '<span class="text-success float-end"> ثبت شد </span>'; else flag = false;
                    content += '<p class="px-4 py-2"><span class="text-dark px-3 py-1">"' + item._query.title + '"</span> '+ status +' </p>';
                });

                if (flag) {
                    content += '<p class="px-4 py-2"><span class="text-primary px-3 py-1 text-center"> <i class="bi bi-gift"></i> هدیه روزانه به شما تعلق گرفت</span></p>';
                }

                Toastify({
                    text: content,
                    duration: 7000,
                    close:true,
                    gravity:"top",
                    position: "center",
                    backgroundColor: "rgb(247,249,255)",
                }).showToast();
            },
            error: function (e) {
                console.log("error!!!", e);
            }
        });
        e.preventDefault();
    }
});
