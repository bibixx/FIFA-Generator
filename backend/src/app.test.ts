import request from 'supertest';
import { Router } from 'express';

import app from './app';

jest.mock('./routes', () => {
  const router = Router();

  router.get('/fail', () => {
    throw new Error();
  });

  return router;
});

describe('app', () => {
  describe('logger', () => {
  });

  describe('errors', () => {
    it('should return 404 if route has not been found', async () => {
      await request(app)
        .get('/no-such-path')
        .expect(404);
    });

    it('should return 500 if an unexpected error occurred', async () => {
      await request(app)
        .get('/fail')
        .expect(500);
    });
  });
});
