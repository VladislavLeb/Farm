<?php
abstract class Animal
{
    function getId()
    {
    	return $this->id;
    }
    
    function getType()
    {
    	return $this->type;
    }
    
    abstract public function collectProducts();
}

class Cow extends Animal
{
    protected $id;
    protected $type;
    private $maxAmount;
    private $minAmount;
    
    function __construct($type)
    { 
        $this->id = uniqid();
        $this->type = $type;
        $this->minAmount = 8;
        $this->maxAmount = 12;
    }
    
    function collectProducts()
    {
    	return ["name" => "milk", "amount" => rand($this->minAmount, $this->maxAmount)];
    }
    
}

class Chicken extends Animal
{
	protected $id;
    protected $type;
    private $maxAmount;
    private $minAmount;
    
    function __construct($type)
    { 
        $this->id = uniqid();
        $this->type = $type;
        $this->minAmount = 0;
        $this->maxAmount = 1;
    }
    
    function collectProducts()
    {
    	return ["name" => "egg", "amount" => rand($this->minAmount, $this->maxAmount)];
    }

}

class AnimalFactory
{
	public function create($type)
	{
		switch ($type) {
			case 'Cow':
				return new Cow($type);
			case 'Chicken':
				return new Chicken($type);
			default:
				throw new Exception('Cant find animal type='.$type);
		}
	}
}

class Farm
{
	private $animalFactory;
	private $animals = [];
	private $animalTypes = [];

	function __construct()
    { 
        $this->animalFactory = new AnimalFactory();
    }
    
	public function addAnimal($type) // create and store animal
	{
		array_push($this->animals, $this->animalFactory->create($type));
		if (isset($this->animalTypes[$type])) {
			$this->animalTypes[$type]++;
		} else {
			$this->animalTypes[$type] = 1;
		}
	}
	
	public function getAllAnimals() // get list of all animals on farm
	{
		print("List of all animals:\n");
		foreach ($this->animals as $animal) {
			print('Animal of type '.$animal->getType().' has unique id='.$animal->getId()."\n");
		}
		print('Total:'.count($this->animals));
	}
	
	public function getAnimalsGrouped() // get amount of animals grouped by type
	{
		print("List of all animals:\n");
		print_r($this->animalTypes);
	}
	
	public function collectProducts($days) // collect products from animals
	{
		$collectedProducts = [];
		
		for ($i = 0; $i < $days; $i++) {
			foreach ($this->animals as $animal) {
				$animalProduct = $animal->collectProducts();
				// print_r('Collect '.$animalProduct['name'].' with amount '.$animalProduct['amount']."\n"); // uncomment to see products from every animal
				if (isset($collectedProducts[$animalProduct['name']])) {
					$collectedProducts[$animalProduct['name']] += $animalProduct['amount'];
				} else {
					$collectedProducts[$animalProduct['name']] = $animalProduct['amount'];
				}
			}
		}
		print("Collected products for ".$days." days:\n");
		print_r($collectedProducts);
	}
}

$week = 7;

$farm = new Farm;

for ($i = 0; $i < 10; $i++) {
	$farm->addAnimal('Cow');
}

for ($i = 0; $i < 20; $i++) {
	$farm->addAnimal('Chicken');
}

$farm->getAnimalsGrouped();

$farm->collectProducts($week);

$farm->addAnimal('Cow');

for ($i = 0; $i < 5; $i++) {
	$farm->addAnimal('Chicken');
}

$farm->getAnimalsGrouped();

$farm->collectProducts($week);
