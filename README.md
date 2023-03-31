> Wordpress Pukara starter theme

## Usage

#### 1. Clone the repository

```bash
git clone https://github.com/PukaraES/wordpress-boilerplate.git
```

#### 2. Remove the .git folder

```bash
cd wordpress-boilerplate && rm -rf .git/
```

#### 3. Configure your envs and start the local dev environment with docker-compose

```bash
docker-compose up -d
```

#### 4. Install theme dependencies

```bash
cd themes/pukara-theme && yarn
```

#### 5. Run gulp script to compile assets inside the theme folder

```bash
npx gulp
```

#### 6. Install the theme and start to hack

