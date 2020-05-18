<?php

namespace App\Entity;

class Team
{
    private const GOALKEEPERS = 'В';
    private const DEFENSES = 'З';
    private const MIDFIELDERS = 'П';
    private const FORWARDS = 'Н';

    public const POSITIONS = [
        self::GOALKEEPERS,
        self::DEFENSES,
        self::MIDFIELDERS,
        self::FORWARDS,
    ];
    private string $name;
    private string $country;
    private string $logo;
    /**
     * @var Player[]
     */
    private array $players;
    private array $positionsTime;
    private string $coach;
    private int $goals;

    public function __construct(string $name, string $country, string $logo, array $players, string $coach)
    {
        $this->assertCorrectPlayers($players);

        $this->name = $name;
        $this->country = $country;
        $this->logo = $logo;
        $this->players = $players;
        $this->coach = $coach;
        $this->goals = 0;
        $this->positionsTime = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @return Player[]
     */
    public function getPlayersOnField(): array
    {
        return array_filter($this->players, function (Player $player) {
            return $player->isPlay();
        });
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getPlayer(int $number): Player
    {
        foreach ($this->players as $player) {
            if ($player->getNumber() === $number) {
                return $player;
            }
        }

        throw new \Exception(
            sprintf(
                'Player with number "%d" not play in team "%s".',
                $number,
                $this->name
            )
        );
    }

    public function getCoach(): string
    {
        return $this->coach;
    }

    public function addGoal(): void
    {
        $this->goals += 1;
    }

    public function getGoals(): int
    {
        return $this->goals;
    }


    private function assertCorrectPlayers(array $players)
    {
        foreach ($players as $player) {
            if (!($player instanceof Player)) {
                throw new \Exception(
                    sprintf(
                        'Player should be instance of "%s". "%s" given.',
                        Player::class,
                        get_class($player)
                    )
                );
            }
        }
    }

    public function getTotalTimeByThePosition($positionName)
    {
        return array_reduce($this->getPlayersByPosition($positionName), function ($totalTime, $player) {
            $totalTime += $player->getPlayTime();
            return $totalTime;
        });
    }


    private function getPlayersByPosition($positionName)
    {
        return array_filter($this->getPlayers(), function ($player) use ($positionName) {
            $position = $player->getPosition();
            return $position == $positionName;
        });
    }

    public function setPositionsTime($positionsTime, $position): void
    {
        $this->positionsTime[] = [
            'name' => $position,
            'time' => $positionsTime
        ];
    }

    /**
     * @return array
     */
    public function getPositionsTime(): array
    {
        return $this->positionsTime;
    }
}
