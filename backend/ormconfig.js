module.exports = {
  type: 'postgres',
  host: 'localhost',
  port: 54320,
  username: 'postgres',
  password: 'example',
  database: 'postgres',
  entities: ['src/modules/**/entity.ts'],
  cli: {
    entitiesDir: 'src/modules/**/entity.ts',
    migrationsDir: 'migrations',
  },
  migrationsTableName: 'migrations',
  migrations: ['migrations/*.ts'],
};
