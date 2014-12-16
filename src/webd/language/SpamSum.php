<?php
namespace webd\language;

/**
 * PHP implementation of the SpamSum algorithm, also called ssdeep or
 * context-triggered piecewize hashing
 */
class SpamSum
{
  /**
   * Compute the SpamSum of string using default parameters:
   * length = 64 characters
   * 64 possible letters (Base64)
   * min blocksize = 3
   * block size computed automatically
   * 
   * @param type $string
   * @return \webd\language\SpamSum
   */
  public static function Hash($string) {
    $ss = new SpamSum();
    $ss->HashString($string);
    return $ss;
  }

  const HASH_PRIME = 0x01000193;
  const HASH_INIT = 0x28021967;
  const B64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
  
  protected $SPAMSUM_LENGTH = 64;
  protected $LETTERS = 64;
  protected $BLOCKSIZE = 0;
  protected $MIN_BLOCKSIZE = 3;
  protected $auto_blocksize = true;
  
  protected $left;
  protected $right;
  
  public function SetHashLength($l) {
      $this->SPAMSUM_LENGTH = $l;
  }
  
  public function SetLetters($l) {
      $this->LETTERS = $l;
  }
  
  public function SetMinBlocksize($s) {
      $this->MIN_BLOCKSIZE = $s;
  }
  
  /**
   * Set the blok size manually, so that it won't be computed from the length of
   * the string
   * @param type $s
   */
  public function SetBlockSize($s) {
      $this->BLOCKSIZE = $s;
      $this->auto_blocksize = false;
  }
  
  /**
   * 
   * @param type $string
   * @return \webd\language\SpamSum
   */
  public function HashString($string) {
    $b64 = self::B64;
    $length = strlen($string);

    $in = unpack('C*', $string);

    // Reindex (to start from 0)
    foreach ($in as $k => $v) {
      $in[$k - 1] = $v;
    }
    unset($in[count($in)]);

    // Guess a a reasonable block size
    if ($this->auto_blocksize) {
      $this->BLOCKSIZE = $this->MIN_BLOCKSIZE;
      
      while ($this->BLOCKSIZE * $this->SPAMSUM_LENGTH < $length) {
        $this->BLOCKSIZE = $this->BLOCKSIZE * 2;
      }
    }
    
    again:

    $this->left = array();
    $this->right = array();

    $k = $j = 0;
    $h3 = $h2 = self::HASH_INIT;
    $h = $this->rolling_hash_reset();

    for ($i = 0; $i < $length; $i++) {

      /* at each character we update the rolling hash and the normal 
       * hash. When the rolling hash hits the reset value then we emit 
       * the normal hash as a element of the signature and reset both 
       * hashes
       */
      $h = $this->rolling_hash($in[$i]);
      $h2 = self::sum_hash($in[$i], $h2);
      $h3 = self::sum_hash($in[$i], $h3);

      if ($h % $this->BLOCKSIZE == ($this->BLOCKSIZE - 1)) {
        
        /* we have hit a reset point. We now emit a hash which is based
         * on all chacaters in the piece of the string between the last 
         * reset point and this one
         */
        $this->left[$j] = $b64[$h2 % $this->LETTERS];
        if ($j < $this->SPAMSUM_LENGTH - 1) {
          
          /* we can have a problem with the tail overflowing. The easiest way
           * to cope with this is to only reset the second hash if we have 
           * room for more characters in our signature. This has the effect of
           * combining the last few pieces of the message into a single piece
           */
          $h2 = self::HASH_INIT;
          $j++;
        }
      }

      /* this produces a second signature with a block size of block_size*2. 
       * By producing dual signatures in this way the effect of small changes
       * in the string near a block size boundary is greatly reduced.
       */
      if ($h % ($this->BLOCKSIZE * 2) == (($this->BLOCKSIZE * 2) - 1)) {
        $this->right[$k] = $b64[$h3 % $this->LETTERS];
        if ($k < $this->SPAMSUM_LENGTH / 2 - 1) {
          $h3 = self::HASH_INIT;
          $k++;
        }
      }
    }

    /* If we have anything left then add it to the end. This ensures that the
     * last part of the string is always considered
     */
    if ($h != 0) {
      $this->left[$j] = $b64[$h2 % $this->LETTERS];
      $this->right[$k] = $b64[$h3 % $this->LETTERS];
    }

    /* Our blocksize guess may have been way off - repeat if necessary
     */
    if ($this->auto_blocksize
            && $this->BLOCKSIZE > $this->MIN_BLOCKSIZE
            && $j < $this->SPAMSUM_LENGTH / 2) {
        
      $this->BLOCKSIZE = $this->BLOCKSIZE / 2;
      goto again;
    }

    return $this;
  }
  
  public function __toString() {
    return 
        $this->BLOCKSIZE . ":" . $this->Left() . ":" . $this->Right();
  }
  
  public function BlockSize() {
    return $this->BLOCKSIZE;
  }
  
  public function Left() {
    return implode("", $this->left);
  }
  
  public function Right() {
    return implode("", $this->right);
  }
  
  /* A simple non-rolling hash, based on the FNV hash
   */
  protected static function sum_hash($c, $h) {
    $h = ($h * self::HASH_PRIME) % pow(2, 32);
    $h = ($h ^ $c) % pow(2, 32);
    return $h;
  }
  

  /* A rolling hash, based on the Adler checksum. By using a rolling hash
   * we can perform auto resynchronisation after inserts/deletes internally,
   * h1 is the sum of the bytes in the window and h2 is the sum of the bytes 
   * times the index h3 is a shift/xor based rolling hash, and is mostly 
   * needed to ensure that we can cope with large blocksize values
   */
  const ROLLING_WINDOW = 7;
  
  protected $rolling_window = array();
  protected $rolling_h1;
  protected $rolling_h2;
  protected $rolling_h3;
  protected $rolling_n;

  protected function rolling_hash($c) {
    $this->rolling_h2 -= $this->rolling_h1;
    $this->rolling_h2 += self::ROLLING_WINDOW * $c;

    $this->rolling_h1 += $c;
    $this->rolling_h1 -= $this->rolling_window[$this->rolling_n % self::ROLLING_WINDOW];

    $this->rolling_window[$this->rolling_n % self::ROLLING_WINDOW] = $c;
    $this->rolling_n++;

    $this->rolling_h3 = ($this->rolling_h3 << 5) & 0xFFFFFFFF;
    $this->rolling_h3 ^= $c;

    return $this->rolling_h1 + $this->rolling_h2 + $this->rolling_h3;
  }

  protected function rolling_hash_reset() {
    for ($i = 0; $i < self::ROLLING_WINDOW; $i++) {
      $this->rolling_window[$i] = 0;
    }

    $this->rolling_h1 = 0;
    $this->rolling_h2 = 0;
    $this->rolling_h3 = 0;
    $this->rolling_n = 0;

    return 0;
  }

}