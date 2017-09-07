<?php
//TODO. not sure if I should use require or include. ask Nonu later
require_once('ethereum-php/ethereum.php');
$ethNode = '192.168.1.131';
$ethereum = new Ethereum('localhost', 8545);
echo "starting etherum\n";
  function findUserTransaction($address){
    $txs = getTransactionsByAddress($address,getReliableBlockNo(),
    getLastCachedBlockNoByAddress($address));
    return $txs;
  }
  function getLastCachedBlockNoByAddress($address)
  {
    //TODO. Need to add database suppport. For now return the blocknum when pre sale started
    global $ethereum;
    //return $ethereum->eth_blockNumber() -6;
    return '0x40d3c0' - 0;
   //return 11111;
  }

  function getReliableBlockNo(){
    /* return the  in ether blockchain which was confirmed in n blocks
    TODO check with Nanu if the confirmationNo is required
    */
    global $ethereum;
    $confirmNo = 5;
    //return $ethereum->eth_blockNumber() - 5;
    return '0x40d3c0' - 0;
  }

  function getTransactionsByAddress($address, $startBlockNumber, $endBlockNumber) {
    global $ethereum;
    for ($i = $startBlockNumber; $i <= $endBlockNumber; $i++) {
      $hexAddress =  '0x' . dechex($i);
      $block = $ethereum->eth_getBlockByNumber($hexAddress, true);
      if ($block !== null && $block->transactions !== null) {
       forEach($block->transactions as $tx){
        if($tx->to == $address){
          $result[]=$tx;
        }
       }
    }
  }
  //TODO. add this to cache
  return $result;
}
$testAddress = '0x90bd44f0bf4ee16a0cf94fc2154616bebe9f7b06';
echo json_encode(getTransactionsByAddress($testAddress,getLastCachedBlockNoByAddress($testAddress),getReliableBlockNo()));
?>