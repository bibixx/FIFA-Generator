import { createConnection } from 'typeorm';

// createConnection method will automatically read connection options
// from your ormconfig file or environment variables
const connection = await createConnection();
