import {
  createLogger, LoggerOptions, transports, format,
} from 'winston';
import { StreamOptions } from 'morgan';
import isProduction from '../isProduction';

const loggerFormat = format.combine(
  format.timestamp({
    format: 'YYYY-MM-DD HH:mm:ss',
  }),
  format.printf((info): string => `[${info.timestamp}] ${info.level}: ${info.message}`),
);

const options: LoggerOptions = {
  transports: [
    new transports.Console({
      level: isProduction() ? 'info' : 'debug',
      format: format.combine(
        loggerFormat,
        format.colorize({ all: true }),
      ),
    }),
    new transports.File({
      filename: 'app.log',
      level: 'info',
      handleExceptions: true,
      maxsize: 5242880,
      maxFiles: 5,
      format: format.combine(
        format.timestamp({
          format: 'YYYY-MM-DD HH:mm:ss',
        }),
        format.json(),
      ),
    }),
  ],
};

const logger = createLogger(options);

export class MorganStream implements StreamOptions {
  // eslint-disable-next-line class-methods-use-this
  public write(text: string): void {
    logger.info(text.trimRight());
  }
}

export default logger;
