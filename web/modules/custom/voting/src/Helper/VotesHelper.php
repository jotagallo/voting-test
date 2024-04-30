<?php

namespace Drupal\voting\Helper;

trait VotesHelper {
  /**
   * Helper function to check if user has voted on a given node.
   */
  public function checkIfVoted($nid, $uid = NULL) {
    $uid = empty($uid) ? \Drupal::currentUser()->id() : $uid;
    $rows = $this->select([
      ['v.uid', $uid, '='],
      ['v.nid', $nid, '=']
    ]);
    return !empty($rows);
  }
}