<?php


abstract class Entity
{
    protected int $id;

    /**
     * Constructeur de la classe qui assigne les données spécifiées en paramètre aux attributs correspondants.
     * @param $datas array Les valeurs à assigner
     * @return void
     */
    public function __construct($datas = [])
    {
        if (!empty($datas)) // Si on a spécifié des valeurs, alors on hydrate l'objet.
        {
            $this->hydrate($datas);
        }
    }

    /**
     * Affecte les valeurs spécifiées aux attributs correspondant.
     * @param $datas array Les données à assigner
     * @return void
     */
    public function hydrate(array $datas): void
    {

        foreach ($datas as $property => $value) {
            $method = 'set' . ucfirst($property);

            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }

    /**
     * Permet de savoir si une entité est valide.
     * @return bool
     */
    abstract public function isValid(): bool;

    /**
     * Permet de savoir si une entité spécifique existe.
     * @return bool
     */
    public function exist(): bool
    {
        return !empty($this->id);
    }

    /**
     * Getter : id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Setter : id
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}