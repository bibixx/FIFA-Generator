import express from 'express';
import {
  createTournament,
  getTournaments,
  getTournamentById,
  deleteTournamentById,
} from 'app/modules/tournaments/controller';

const router = express.Router()
  .post('/', createTournament)
  .get('/', getTournaments)
  .get('/:uuid', getTournamentById)
  .delete('/:uuid', deleteTournamentById);

export default router;
