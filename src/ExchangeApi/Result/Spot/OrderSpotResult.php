<?php

namespace ExchangeApi\Result\Spot;

class OrderSpotResult
{
    private string $id;
    private string $symbol;
    private float $price;
    private float $originalQuantity;
    private float $executedQuantity;
    private string $status;
    private string $timeInForce;
    private string $type;
    private string $side;
    private float $stopPrice;
    private int $timeInMs;

    public function __construct(
        string $id, string $symbol, float $price, float $originalQuantity, float $executedQuantity,
        string $status, string $timeInForce, string $type, string $side, float $stopPrice, int $timeInMs)
    {
        $this->id = $id;
        $this->symbol = $symbol;
        $this->price = $price;
        $this->originalQuantity = $originalQuantity;
        $this->executedQuantity = $executedQuantity;
        $this->status = $status;
        $this->timeInForce = $timeInForce;
        $this->type = $type;
        $this->side = $side;
        $this->stopPrice = $stopPrice;
        $this->timeInMs = $timeInMs;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getOriginalQuantity(): float
    {
        return $this->originalQuantity;
    }

    public function getExecutedQuantity(): float
    {
        return $this->executedQuantity;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTimeInForce(): string
    {
        return $this->timeInForce;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSide(): string
    {
        return $this->side;
    }

    public function getStopPrice(): float
    {
        return $this->stopPrice;
    }

    public function getTimeInMs(): string
    {
        return $this->timeInMs;
    }

}