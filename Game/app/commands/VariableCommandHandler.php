<?php

// LEFT HAND RULES:
// Specify ClassType varName
// Terminate with ; or extend with = initial value (RIGHT HAND)

// RIGHT HAND RULES
// Specify expression
// Expression can be new ClassType([optional params, comma separated]);

// regex for AType varName = new
// ([A-Za-z]{1}[A-Za-z0-9]*)[\s*]([A-Za-z]{1}[A-Za-z0-9]*)[\s*]=[\s.]new[\s.]\1\(([A-Za-z0-9"',\s\[\]]*)\);
// validation: \1 and \3 should be equal or \3 should be implement or extend of \1

class ValidateParse
{
  public $isValid = false;
  public $output = null;
}

function NullLiteral($arg)
{
  $vp = new ValidateParse();
  $vp->isValid = $arg === "null";
  $vp->output = ($vp->isValid ? $arg : null);
  return $vp;
}

function EcscapeSequence($arg) {
  /*
    EscapeSequence:
    \ b (backspace BS, Unicode \u0008)
    \ t (horizontal tab HT, Unicode \u0009)
    \ n (linefeed LF, Unicode \u000a)
    \ f (form feed FF, Unicode \u000c)
    \ r (carriage return CR, Unicode \u000d)
    \ " (double quote ", Unicode \u0022)
    \ ' (single quote ', Unicode \u0027)
    \ \ (backslash \, Unicode \u005c)
    OctalEscape (octal value, Unicode \u0000 to \u00ff)
 */
 $vp = new ValidateParse();
 switch ($arg)
 {
   case '\b': case '\t': case '\n': case '\f': case '\r': case '\"': case: "\\'": case '\\':
    $vp->isValid = true;
    $vp->output = $arg;
    return $vp;
 }
 if (!$vp->isValid)
 {
   return OctalEscape($arg);
 }
}

function OctalEscape($arg) {
  /*
      OctalEscape:
    \ OctalDigit
    \ OctalDigit OctalDigit
    \ ZeroToThree OctalDigit OctalDigit
  */
  $output = "";
  switch(strlen($arg)) {
    case 3:

  }
}

function OctalDigit() {
  /*
    OctalDigit:
    (one of)
    0 1 2 3 4 5 6 7
  */
}

function ZeroToThree() {
  /*
    ZeroToThree:
    (one of)
    0 1 2 3
  */
}
