module.exports = {
  type: 'postgres',
  host: 'db_postgres',
  port: 5432,
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
