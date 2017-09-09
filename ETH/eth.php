<?php
require_once ('ethereum-php/ethereum.php');

/*port 8545 is not visible. create SSH tunnel and use localhost
ssh -fN -L 8545:127.0.0.1:8545 root@192.168.1.131
*/

// $ethNode = '192.168.1.131';

$ethNode = 'localhost';
$ethereum = new Ethereum($ethNode, 8545);

function getLastCachedBlockNoByAddress($address)
  {

  // TODO.It is very costly to scan all eth blocks.
  // May need to store the transaction information in a database(mongodb)
  // For now, just returen the last 1000 block

  global $ethereum;
  return $ethereum->eth_blockNumber() - 1000;
  }

function getReliableBlockNo()
  {

  // instead of the latest block,return

  global $ethereum;
  $confirmNo = 5;
  return $ethereum->eth_blockNumber() - 5;
  }

function getTransactionsByAddress($address, $startBlockNumber, $endBlockNumber)
  {
  global $ethereum;
  for ($i = $startBlockNumber; $i <= $endBlockNumber; $i++)
    {
    $hexAddress = '0x' . dechex($i);
    $block = $ethereum->eth_getBlockByNumber($hexAddress, true);
    if ($block !== null && $block->transactions !== null)
      {
      forEach($block->transactions as $tx)
        {
        if ($tx->to == $address)
          {
          $result[] = $tx;
          }
        }
      }
    }

  // TODO. add this to cache

  return $result;
  }

$testAddress = '0x90bd44f0bf4ee16a0cf94fc2154616bebe9f7b06';
$txs = getTransactionsByAddress($testAddress, getLastCachedBlockNoByAddress($testAddress) , getReliableBlockNo());
echo json_encode($txs);
?>