# SHIFT
1) AM: Morning Booking planned to end no later thy 1:00pm.
    Min. billing 3 or 4hrs depending on Payer in Col"C".


2) FD: Full-Day booking planned to go beyond 1:00pm.
     Min. billing 6 or 7hrs depending on Payer in Col"C".


3) PM: Booking starting at or after 12:00 noon. Min billing 3hrs


4) D: any booking between 6:00AM and 6:00PM if Payer in
     Col"C"=CBSA and Col"E"=QC.  Min. billing 3hrs x $50.


5) N: any booking between 6:00pm and 6:00am when Payer
      in Col"C"=CBSA and Col"E"=QC. Min. billing 3hrs x $75.


<!-- ############################## -->

# INTERP

OPEN THE COMMENT TO SEE IT IN FULL

# Cel M3 Formula

<!-- Column Names: -->
Col"C" = Payer
Col"E" = Province
Col"H" = Shift
Col"J" = From Time
Col"K" = To Time
Col"L" = Billable Hours
Col"M" = Interp

1. Formula must make Col"M"= Col "L" but in decimal format, and...
     1.a(Done)  In addition to point "1" in Cel M3 formula
          When Col"C"=CBSA, Col"E"=ON, Col"H"=FD and Col"K"≤12:00 (noon)
          if Col"L" <7:00, make Col"M"=7.00 ands
          if Col"L" > 7:00 contains minutes, see point "2.b" in Cel M3 formula

     1.b(Done)  In addition to point "1" in formula in Cel M3,
          When Col"C"=CBSA, Col"E"=ON, Col"H"=AM and Col "K"≤12:00:
          if Col "L" ≤4:00 make Col"M" = 4.00; and
          if Col"L">4:00 contains minutes, see point "2.b" in Cel M3 formula

     1.c(Done)  In addition to point "1" in formula in Cel M3,
          When Col"C"=CBSA, Col"E"=ON and Col"H"=PM
          if Col"L"<3:00, make Col"M"=3hrs
          if Col "L">3:00 contains minutes, see point "2.b" in Cel M3

     1.d(Done)  In addition to point "1" in Cel M3 formula
          When Col"C"=CBSA, Col"E"=QC, Col"H"=FD and Col"K"≤18:00...
          If Col"L"<6:00 make Col"M"=6.00
          if Col"L">6:00 contains minutes, see point "2.b" in Cel M3 formula

     1.e(Done)  In addition to point "1" in Cel M3 formula
          When Cols C=CBSA + E=QC + H=D + J ≥06:00 + K≤18:00...
          If Col"L"<3:00 make Col"M"=3.00
          if Col"L">3:00 contains minutes, see point "2.b" in Cel M3 formula
          Note: Only for CBSA QC you will later see in Col"AG", that pay rate changes to $50/hr from 6:00am-6:00pm, to $75/hr from 6:00pm-6:00:am.

     1.f(Done)  In addition to point "1" in Cel M3 formula
          When Cols C=CBSA + E=QC + H=D + J ≥18:00 + K≤06:00
          If Col"L"<3:00 make Col"M"=3.00, and
          if Col"L">3:00 containis minutes, see point "2.b" in Cel M3 formula
          Note in Cel N20 applies here too.

     1.g(Done)  In addition to point "1" in Cel M3 formula,
          When Col"C"=MAG (or ≠CBSA, IRB, IRCC or CIC), Col"H"=FD and Col"K"≤13:00;
          if Col"L"<6:00, make Col"M"=6.00hrs and
          if Col"L">6:00 contains minutes, see point "2.a" in Cel M3 Formula

     1.h(Done)  In addition to point "1" in Cel M3 formula,
          When Col"C"=MAG (or ≠CBSA, IRB, IRCC or CIC), Col"H"=AM and Col"K"≤13:00;
          if Col"L"<3:00, make Col"M"= 3.00hrs and
          if Col"L">3:00 contains minutes, see point "2.a" inCel M3 Formula

     1.i(Done)  In addition to point "1" in Cel M3 formula,
          When Col"C" =MAG (or ≠CBSA, IRB, IRCC or CIC), Col"H"=AM and Col"K">13:00;
          if Col"L"<6:00, make Col"M"= 6.00hrs and
          if Col"L">6:00 contains minutes, see point "2.a" inCel M3 Formula

     1.j(Done)  In addition to point "1" in Cel M3 formula,
          When Col"C"=MAG (or ≠CBSA, IRB, IRCC or CIC), Col"H"=PM and Col"K≥12:00" (noon)
          if Col"L"≤3:00, make Col"M"= 3.00hrs
          if Col"L">3:00 contains minutes, see point "2.a" in Cel M3 Formula

     1.k(Done)  In addition to point "1" in Cel M3 Formula
          When Col "C"=IRB, IRCC or CIC and Col"H"=FD;
          if Col"J"≤12:00 (noon) and
          If Col"L"<7:00 make Col"M"=7.00

     1.l(Done)  In addition to point "1" in Cel M3 Formula
          When Col "C"=IRB, IRCC or CIC and Col"H"=FD;
          if Col"J"≤12:00 (noon) and
          if Col"L">7:00 containing minutes, see point "2.b" in Cel M3 formula

     1.m(Done)  In addition to point "1" in Cel M3 Formula
          When Col "C"=IRB, IRCC or CIC and Col"H"=AM;
          if Col"K"≤12:00 (noon) and
          If Col"L"<4:00 make Col"M"=4.00

     1.n(Done)  In addition to point "1" in Cel M3 Formula
          When Col "C"=IRB, IRCC or CIC and Col"H"=AM;
          if Col"K"≤12:00 (noon) and
          if Col"L">4:00 containing minutes, see point "2.b" in Cel M3 formula

     1.o(Done)  In addition to point "1" in Cel M3 Formula
          When Col "C"=IRB, IRCC or CIC and Col"H"=PM; 
          if Col"J"≥12:00 (noon) and
          If Col"L"<3:00 make Col"M"=3.00
          if Col"L">3:00 containing minutes, see point "2.b" in Cel M3 formula

     1.p(Done)  in else case, make Col"M"=Col"L" in decimal format


2. if Col"L" contains minutes, the formula must do the following:
     2.a.(Done) When Col "C"≠CBSA, IRB, IRCC or CIC,
           i.   if minutes >10, round up Col"M" to the next whole hour.
           ii. if minutes ≤10, disard them.
     2.b.(Done) When Col "C" = CBSA, IRB, IRCC or CIC, if minutes ≥1,
           round up Col"M" to the next 1/4 hr
NOTE: The hours in red you see in Col "M" were manually entered  to ilustrate the desired result to be produced by the formula.



<!-- ############################## -->



# HST

To be calculated by formula:
if Col"E"=QC, then Col"V"=Col"U" x 0.1497
if Col"E"=ON, then Col"V"=Col"U" x 0.13
if Col"E"≠ON or QC, then Col"V"=Col"U" x 0.13
by default, but must allow manual input for other provinces when ≠0.13


<!-- ############################## -->


# $/HR

<!-- Column Names: -->
Col"C" = Payer
Col"E" = Province
Col"B" = Date
Col"AG" = $/hr
Col"J" = From Time
Col"K" = To Time
Col"G" = Code
Col"I" = Type


1) When Col"C"=CBSA, Col"E"=ON 
    1) if Col"B"=Mon, Tue, Wed, Thu or Fri (except holiday), then Col"AG"= $50 
    or  
    2) if Col"B"=Sat, Sun or Holiday; then Col"AG"=$75

2) When Col"C"=CBSA, Col"E"=QC and 
    1) Col"B"= Mon, Tue, Wed,Thu or Fri (except if a holiday)...
        1) if Cols"J and K" ≤18:00, then Col"AG"=$50; 
        or
        2) if Cols"J and K">18:00, then Col"AG"=$75

    2) When Col"C"=CBSA, Col"E"=QC and Col"B"=
        Sat, Sun or holiday, then Col"AG"=$75



3) 
    1) When Col"C"=MAG and Col"G"= 12xx, 31xx, 47xx or 48xx
        if Col"I"= P; then Col"AG"= $70 or
        if Col"I"≠ P; then Col"AG"= $60
    2) When Col"C"=MAG but Col"G"≠12xx, 31xx, 47xx or 48xx
        the $/hr value will be entered manually

4)  When Col"C"=IRB, IRCC or CIC:
    1) if Col"B"= Mon, Tue, Wed, Thu or Fri; then Col"AG"=$50 
    or
    2) if Col"B" = Sat, Sun or Holiday; then Col"AG"=$75
