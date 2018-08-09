<?php

namespace App\Models\Entity;

/**
 * @Entity
 * @Table(name="tasks")
 */
class Task {

    /**
     * @var int
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="IDENTITY")
     * @SequenceGenerator(sequenceName="tasks_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var string|null
     * @Column(type="string", nullable=true))
     */
    protected $text;

    /**
     * @var string|null
     * @Column(type="string", nullable=false)
     */
    protected $userName;

    /**
     * @var string|null
     * @Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @var string|null
     * @Column(type="string", nullable=false)
     */
    protected $image;

    /**
     * @var boolean|null
     * @Column(type="boolean", nullable=true)
     */
    protected $executed;

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param null|string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return null|string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param null|string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param null|string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return bool|null
     */
    public function getExecuted()
    {
        return $this->executed;
    }

    /**
     * @param bool|null $executed
     */
    public function setExecuted($executed)
    {
        $this->executed = $executed;
    }



}