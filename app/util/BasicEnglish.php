<?php

function is_vowel($letter) {
  $letter = strtolower($letter);
  return $letter === 'a'
      || $letter === 'e'
      || $letter === 'i'
      || $letter === 'o'
      || $letter === 'u';
}

function insertAOrAn($beforeWord) {
  if (is_vowel(substr($beforeWord, 0, 1)))
    return "an $beforeWord";
  return "a $beforeWord";
}

/**
 * Join a string with a natural language conjunction at the end.
 * Source:
 *   https://gist.github.com/dan-sprog/e01b8712d6538510dd9c
 */
function natural_language_join(array $list, $conjunction = 'and') {
  $last = array_pop($list);
  if ($list) {
    if (count($list) == 1)
      return implode(', ', $list) . ' ' . $conjunction . ' ' . $last;
    else if (count($list) > 1)
      return implode(', ', $list) . ', ' . $conjunction . ' ' . $last;
  }
  return $last;
}
