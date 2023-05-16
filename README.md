# 🍃 Air Quality API

<p align="center">
    <img src="./docs/cover.png" height="300" alt="Air Quality">
    <p align="center">
        <a href="https://github.com/mihaichris/air-quality/actions"><img alt="GitHub Workflow Status (master)" src="https://github.com/mihaichris/air-quality/actions/workflows/tests.yml/badge.svg"></a>
        <a href="https://app.codecov.io/gh/mihaichris/air-quality"><img alt="Codecov" src="https://img.shields.io/codecov/c/github/mihaichris/air-quality?color=%23FC0177&label=Codecov"></a>
        <a href="https://github.com/mihaichris/air-quality/issues"><img alt="Open Issues" src="https://img.shields.io/github/issues/mihaichris/air-quality"></a>
    </p>
</p>



# Description
Air-quality allows you to retrieve the values for different pollutants and pollen from a region based on specific coordinates 🍃. The library is powered by [OpenMeteo](https://open-meteo.com/en/docs/air-quality-api) air quality API. 

# 🚀 Installation

```php
$ composer require mihaichris/air-quality
```

# Basic Usage

```php
use Air\Quality\AirQuality;

//Getting the air quality from Bucharest.
$airQuality = new AirQuality(44.43, 26.11);

//Get the air quality now.
$airQuality->getNow();

//Get the air quality in the past days.
$airQuality->getPast(2);

//Get the air quality between specific dates.
$airQuality->getBetweenDates('2023-01-01', '2023-02-01');

```

# 👨‍💻 Author
Mihai-Cristian Făgădău
 * Github: [@mihaichris](https://github.com/mihaichris)
 * LinkedIn: [in/mihai-fagadau](https://www.linkedin.com/in/mihai-fagadau/)
 * Website: [mihaifagadau.dev](https://mihaifagadau.dev)

# 🤝 Contributing
Contributing details can be found at [here](./CONTRIBUTING.md).

# 📝 License
This project is [MIT](https://opensource.org/licenses/MIT) licensed.
