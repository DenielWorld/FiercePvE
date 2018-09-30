<?php

namespace HayaoPVE\window;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandExecutor;
use pocketmine\scheduler\Task;
//use pocketmine\scheduler\CallbackTask;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\PressurePlate;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\Attribute;
use pocketmine\entity\Effect;
use pocketmine\entity\Zombie;
use pocketmine\entity\Skeleton;
use pocketmine\entity\Enderman;
use pocketmine\entity\Villager;
use pocketmine\entity\PigZombie;
use pocketmine\entity\Creeper;
use pocketmine\entity\Spider;
use pocketmine\entity\Witch;
use pocketmine\entity\IronGolem;
use pocketmine\entity\Blaze;
use pocketmine\entity\Slime;
use pocketmine\entity\WitherSkeleton;
use pocketmine\entity\Horse;
use pocketmine\entity\Donkey;
use pocketmine\entity\Mule;
use pocketmine\entity\SkeletonHorse;
use pocketmine\entity\ZombieHorse;
use pocketmine\entity\Stray;
use pocketmine\entity\Husk;
use pocketmine\entity\Mooshroom;
use pocketmine\entity\FallingSand;
use pocketmine\entity\Item as DroppedItem;
use pocketmine\entity\Skin;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\ItemFrameDropItemEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityCombustByEntityEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryPickupArrowEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerTextPreSendEvent;
use pocketmine\event\player\PlayerAchievementAwardedEvent;
use pocketmine\event\player\PlayerAnimationEvent;
use pocketmine\event\player\PlayerBedEnterEvent;
use pocketmine\event\player\PlayerBedLeaveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerHungerChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerToggleSprintEvent;
use pocketmine\event\player\PlayerUseFishingRodEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\event\TextContainer;
use pocketmine\event\Timings;
use pocketmine\event\TranslationContainer;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\AnvilInventory;
use pocketmine\inventory\BaseTransaction;
use pocketmine\inventory\BigShapedRecipe;
use pocketmine\inventory\BigShapelessRecipe;
use pocketmine\inventory\CraftingManager;
use pocketmine\inventory\DropItemTransaction;
use pocketmine\inventory\EnchantInventory;
use pocketmine\inventory\FurnaceInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\item\enchantment\ProtectionEnchantment;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Armor;
use pocketmine\item\FoodSource;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\item\Durable;
use pocketmine\level\ChunkLoader;
use pocketmine\level\Explosion;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\level\sound\LaunchSound;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\WeakPosition;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\metadata\MetadataValue;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\nbt\tag\NoDynamicFieldsTrait;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\Network;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\AddHangingEntityPacket;
use pocketmine\network\mcpe\protocol\AddItemEntityPacket;
use pocketmine\network\mcpe\protocol\AddItemPacket;
use pocketmine\network\mcpe\protocol\AddPaintingPacket;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\BlockEntityDataPacket;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\BlockPickRequestPacket;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\network\mcpe\protocol\ChunkRadiusUpdatedPacket;
use pocketmine\network\mcpe\protocol\ClientboundMapItemDataPacket;
use pocketmine\network\mcpe\protocol\ClientToServerHandshakePacket;
use pocketmine\network\mcpe\protocol\CommandBlockUpdatePacket;
use pocketmine\network\mcpe\protocol\CommandStepPacket;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\ContainerSetContentPacket;
use pocketmine\network\mcpe\protocol\ContainerSetDataPacket;
use pocketmine\network\mcpe\protocol\ContainerSetSlotPacket;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\network\mcpe\protocol\CraftingEventPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\DisconnectPacket;
use pocketmine\network\mcpe\protocol\DropItemPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\network\mcpe\protocol\ExplodePacket;
use pocketmine\network\mcpe\protocol\FullChunkDataPacket;
use pocketmine\network\mcpe\protocol\HurtArmorPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\InventoryActionPacket;
use pocketmine\network\mcpe\protocol\ItemFrameDropItemPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\MapInfoRequestPacket;
use pocketmine\network\mcpe\protocol\MobArmorEquipmentPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\MoveEntityPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\EntityFallPacket;
use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\PlayStatusPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\RemoveBlockPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\ReplaceItemInSlotPacket;
use pocketmine\network\mcpe\protocol\RequestChunkRadiusPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkDataPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkRequestPacket;
use pocketmine\network\mcpe\protocol\ResourcePackClientResponsePacket;
use pocketmine\network\mcpe\protocol\ResourcePackDataInfoPacket;
use pocketmine\network\mcpe\protocol\ResourcePacksInfoPacket;
use pocketmine\network\mcpe\protocol\RespawnPacket;
use pocketmine\network\mcpe\protocol\RiderJumpPacket;
use pocketmine\network\mcpe\protocol\ServerToClientHandshakePacket;
use pocketmine\network\mcpe\protocol\SetCommandsEnabledPacket;
use pocketmine\network\mcpe\protocol\SetDifficultyPacket;
use pocketmine\network\mcpe\protocol\SetEntityDataPacket;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\network\mcpe\protocol\SetEntityMotionPacket;
use pocketmine\network\mcpe\protocol\SetHealthPacket;
use pocketmine\network\mcpe\protocol\SetPlayerGameTypePacket;
use pocketmine\network\mcpe\protocol\SetSpawnPositionPacket;
use pocketmine\network\mcpe\protocol\SetTimePacket;
use pocketmine\network\mcpe\protocol\SetTitlePacket;
use pocketmine\network\mcpe\protocol\ShowCreditsPacket;
use pocketmine\network\mcpe\protocol\SpawnExperienceOrbPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\StopSoundPacket;
use pocketmine\network\mcpe\protocol\TakeItemEntityPacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\network\mcpe\protocol\UnknownPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\UpdateTradePacket;
use pocketmine\network\mcpe\protocol\UseItemPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\SourceInterface;
use pocketmine\permission\PermissibleBase;
use pocketmine\permission\PermissionAttachment;
use pocketmine\plugin\Plugin;
use pocketmine\tile\ItemFrame;
use pocketmine\tile\Sign;
use pocketmine\tile\Spawnable;
use pocketmine\tile\Tile;
use pocketmine\utils\Binary;
use pocketmine\utils\Config;
use pocketmine\utils\Color;
use pocketmine\utils\Random;
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;
use pocketmine\Player;
use pocketmine\Server;

use HayaoPVE\main;

class weapon{
	public $main;

	public function __construct(main $main){
		$this->main = $main;
	}

	public function onWeapon($id, $data, $p){
			$name = $p->getName();
				if($id === 500){
					if($data == 0){
				    $data = [
					    'type'    => 'form',
					    'title'   => '強化',
					    'content' => "強化したいのか!",
					    'buttons' => [
		 			    ['text' => "ATK,DEF値の強化"],
		 			    ['text' => "強化に必要な個数の確認"],
		 		     	['text' => "やめる"]
					    ]
				    ];
				    $this->main->createWindow($p, $data, 501);
					}elseif($data == 1){
					$data = [
					    'type'    => 'form',
					    'title'   => '進化',
					    'content' => "強化を5回している必要があるぞ",
					    'buttons' => [
		 			    ['text' => "進化素材"],
		 			    ['text' => "武器進化開始"],
		 		     	['text' => "やめる"]
					    ]
				   ];
				   $this->main->createWindow($p, $data, 505);
					}
				}elseif($id === 501){
					if($data == 0){
						$data = [
							'type'    => 'modal',
							'title'   => "確認",
							'content' => "手に持っているアイテムを強化しますか??\n",
							'button1' => "強化する",
							'button2' => "やめる",
						];
						$this->main->createWindow($p, $data, 504);
					}elseif($data == 1){
						$item = $p->getInventory()->getItemInhand();
						if($item->getNamedTagEntry("kyouka") !== null){
							$kyouka = $item->getNamedTagEntry("kyouka")->getValue();
							$sozai = ($kyouka + 1) ** 2 * 14;
						$data = [
							'type'    => 'form',
							'title'   => "確認",
							'content' => "\n\n次は".$sozai."個必要です\n\n",
							'buttons' => [
		 						['text' => "戻る"]
		 					]
						];
						$this->main->createWindow($p, $data, 503);
						}else{
							$p->sendMessage("§cそのアイテムは強化できぬな");
						}
					}
				}elseif($id === 503){
					if($data == 0){

				   $data = [
					    'type'    => 'form',
					    'title'   => '強化',
					    'content' => "強化したいのか!",
					    'buttons' => [
		 			    ['text' => "ATK,DEF値の強化"],
		 			    ['text' => "強化に必要な個数の確認"],
		 		     	['text' => "やめる"]
					    ]
				    ];
				    $this->main->createWindow($p, $data, 501);
					}
				}elseif($id === 505){
					if($data == 0){
						$hand = $p->getInventory()->getItemInHand();
						if($hand->getNamedTagEntry("sinka")){
							$sinka = $hand->getNamedTagEntry("sinka")->getValue();
							$sozai = $this->citem[$sinka]["sozainame1"];
							$kosuu = $this->citem[$sinka]["kosuu1"];
							$data = [
					  		    'type'    => 'form',
					  		    'title'   => '素材',
					  		    'content' => "\n\n進化素材は§a".$sozai."§rが§a".$kosuu."個§r必要です\n\n",
					  		    'buttons' => [
		 		     			['text' => "もどる"]
					   			]
				  		    ];
				    		$this->main->createWindow($p, $data, 508);
						}else{
							$p->sendMessage("§cその武器は進化できんわ!!");
						}
					}elseif($data == 1){
						$hand = $p->getInventory()->getItemInHand();
						if($hand->getNamedTagEntry("sinka") !== null){
						$data = [
							'type'    => 'modal',
							'title'   => "確認",
							'content' => "手に持っているアイテムを進化しますか??\n",
							'button1' => "進化する",
							'button2' => "やめる",
						];
						$this->main->createWindow($p, $data, 506);
						}else{
							$p->sendMessage("§cその武器は進化できんわ!!");							
						}
					}
				}elseif($id === 504){
					if($data == "true\n"){
						$item = $p->getInventory()->getItemInhand();
						if($item->getNamedTagEntry("kyouka") !== null){
							$kyouka = $item->getNamedTagEntry("kyouka")->getValue();
							$sozaic = ($kyouka + 1) ** 2 * 13;
							$count = $this->main->config[$name]->get("count");
							$sozai = Item::get(378, 0, $sozaic);
							if($kyouka >= 5){
								$p->sendMessage("§cこれ以上は強化できぬ!");
							}else{
								if($count >= $sozaic){
									$this->main->config[$name]->set("count", $count - $sozaic);
									$this->main->config[$name]->save();
									if($item->getNamedTagEntry("name") !== null and $item->getNamedTagEntry("status") !== null){
										$itemname = $item->getNamedTagEntry("name")->getValue();
										$status = $item->getNamedTagEntry("status")->getValue();
										$rarity = $item->getNamedTagEntry("rarity")->getValue();
										$rarity = $this->main->RarityMark($rarity);
										$job = $item->getNamedTagEntry("job")->getValue();
										$type = $item->getNamedTagEntry("type")->getValue();
										$upstatus = floor($status * 0.3);
										if($upstatus < 1){
											$upstatus = 1;
										}else{
											$upstatus = floor($status * 0.2);
										}
										$goukei = intval($status + $upstatus);
										$kyouka = $item->getNamedTagEntry("kyouka")->getValue() + 1;
										$item->setNamedTagEntry(new IntTag("kyouka", $kyouka));
										$item->setNamedTagEntry(new IntTag("status", $goukei));
										$item->setNamedTagEntry(new IntTag("sell", intval($item->getNamedTagEntry("sell")->getValue() * 1.5)));
										$item->setCustomName($itemname."\n§7ATK: ".$goukei."\n§5".$type."属性 強化回数: ".$kyouka."\n§6レア度§r: ".$rarity);
										$p->getInventory()->setItemInhand($item);
										$p->sendMessage("§c[強化屋] ".$goukei."ATKになった!!");
									}elseif($item->getNamedTagEntry("name") !== null and $item->getNamedTagEntry("def") !== null){
										$itemname = $item->getNamedTagEntry("name")->getValue();
										$status = $item->getNamedTagEntry("def")->getValue();
										$job = $item->getNamedTagEntry("job")->getValue();
										$type = $item->getNamedTagEntry("type")->getValue();
										$rarity = $item->getNamedTagEntry("rarity")->getValue();
										$rarity = $this->main->RarityMark($rarity);
										$upstatus = floor($status * 0.3);
										if($upstatus < 1){
											$upstatus = 1;
										}else{
											$upstatus = floor($status * 0.3);
										}
										$kyouka = $item->getNamedTagEntry("kyouka")->getValue() + 1;
										$goukei = intval($status / 4 + $status);
										$item->setNamedTagEntry(new IntTag("kyouka", $kyouka));
										$item->setNamedTagEntry(new intTag("def", $goukei));
										$item->setNamedTagEntry(new IntTag("sell", intval($item->getNamedTagEntry("sell")->getValue() * 1.5)));
										$item->setCustomName($itemname."\n§7ATK: ".$goukei."\n§5".$type."属性 強化回数: ".$kyouka."\n§6レア度§r: ".$rarity);
										$p->getInventory()->setItemInhand($item);
										$p->sendMessage("§c[強化屋] ".$goukei."DEFになった!!");		
									}else{
										$p->sendMessage("§4 ふむ...?なぜかできぬ");				
									}
								}else{
									$p->sendMessage("§c素材が足りません");
								}
							}
						}else{
							$p->sendMessage("§cこの武器は強化できぬ!!");
						}
					}
				}elseif($id === 506){
					if($data == "true\n"){
						$hand = $p->getInventory()->getItemInHand();
						if($hand->getNamedTagEntry("kyouka") !== null){
							$kyouka = $hand->getNamedTagEntry("kyouka")->getValue();
							$sinka = $hand->getNamedTagEntry("sinka")->getValue();
							if(isset($this->main->citem[$sinka]["sozai1"])){
								$item1 = Item::get($this->main->citem[$sinka]["sozai1"], $this->citem[$sinka]["meta1"], $this->main->citem[$sinka]["kosuu1"]);
							}
							if($kyouka >= 5){
								if($p->getInventory()->Contains($item1)){
									$item = Item::get($this->main->citem[$sinka]["id"], $this->citem[$sinka]["meta"], 1)->setCustomName($this->main->citem[$sinka]["cname"]);
									$item->setNamedTagEntry(new StringTag("name", $this->citem[$sinka]["name"]));
									$item->setNamedTagEntry(new IntTag("kyouka", 0));
									if(isset($this->main->citem[$sinka]["atk"])){
										$item->setNamedTagEntry(new IntTag("status", $this->citem[$sinka]["atk"]));
									}
									if(isset($this->main->citem[$sinka]["def"])){
										$item->setNamedTagEntry(new IntTag("def", $this->citem[$sinka]["def"]));
									}
									if(isset($this->main->citem[$sinka]["sell"])){
										$item->setNamedTagEntry(new IntTag("sell", $this->citem[$sinka]["sell"]));
									}
									if(isset($this->main->citem[$sinka]["special"])){
										$item->setNamedTagEntry(new StringTag("special", $this->citem[$sinka]["special"]));
									}
									if(isset($this->main->citem[$sinka]["colorR"]) and isset($this->citem[$sinka]["colorG"]) and isset($this->main->citem[$sinka]["colorB"])){
										$color = new Color($this->main->citem[$sinka]["colorR"], $this->citem[$sinka]["colorG"], $this->main->citem[$sinka]["colorB"]);
										$colorcode = ($color->getR() << 16 | $color->getG() << 8 | $color->getB()) & 0xffffff;
										$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
									}
									$p->getInventory()->removeItem($item1);
									$p->getInventory()->setItemInhand($item);
									$pos = new Vector3($p->x, $p->y, $p->z);
									$pk = new LevelEventPacket;
									$pk->evid = LevelEventPacket::EVENT_SOUND_FIZZ;
									$pk->position = $pos;
									$pk->data = 0;
									$p->dataPacket($pk);
									$p->sendMessage("§c[強化屋] お主の武器を進化したぞ!!");
								}else{
									$p->sendMessage("§c素材が足りないぞ");
								}
							}else{
								$p->sendMessage("§c強化回数が足りておらぬ");
							}
						}
					}
				}
	}
}