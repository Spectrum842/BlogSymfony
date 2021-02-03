<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="category_relations")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="parent")
     */
    private $category_relations;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="category_id")
     */
    private $articles;

    public function __construct()
    {
        $this->category_relations = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
    

    /**
     * @return Collection|self[]
     */
    public function getCategoryRelations(): Collection
    {
        return $this->category_relations;
    }

    public function addCategoryRelation(self $categoryRelation): self
    {
        if (!$this->category_relations->contains($categoryRelation)) {
            $this->category_relations[] = $categoryRelation;
            $categoryRelation->setParent($this);
        }

        return $this;
    }

    public function removeCategoryRelation(self $categoryRelation): self
    {
        if ($this->category_relations->removeElement($categoryRelation)) {
            // set the owning side to null (unless already changed)
            if ($categoryRelation->getParent() === $this) {
                $categoryRelation->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addCategoryId($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeCategoryId($this);
        }

        return $this;
    }
}
